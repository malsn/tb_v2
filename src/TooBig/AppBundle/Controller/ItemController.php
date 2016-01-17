<?php

namespace TooBig\AppBundle\Controller;

use Application\Iphp\CoreBundle\Entity\Rubric;
use TooBig\AppBundle\Entity\Item;
use Iphp\ContentBundle\Controller\ContentController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use TooBig\AppBundle\Entity\RateComment;
use TooBig\AppBundle\Form\ItemForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TooBig\AppBundle\Form\Type\ItemsFilterType;
use TooBig\AppBundle\Form\Type\RateCommentType;
use TooBig\AppBundle\Model\ItemSubscribtionModel;
use Iphp\CoreBundle\Controller\RubricAwareController;

class ItemController extends RubricAwareController
{
    protected $errors;

    /**
     * @Route("/app/item/add/{rubric_id}", name="front_item_add")
     */
    public function addAction($rubric_id, Request $request)
    {
        $rubric = $this->get('rubric_model')->getRubricById($rubric_id);

        $record = new Item();
        $record->setRubric($rubric);
        $record->setEnabled(true);
        $record->setHits(0);

        $user = $this->get('security.context')->getToken()->getUser();

        if (is_object($user)) {

            $form = $this->createForm(
                new ItemForm($this->get('router')),
                $record
            );

            /* добавление в форму данных загрузчика фотографий */
            $editId = $request->get('editId');
            if (!preg_match('/^\d+$/', $editId))
            {
                $editId = sprintf('%09d', mt_rand(0, 1999999999));
                if ($record->getId())
                {
                    $this->get('punk_ave.file_uploader')->syncFiles(
                        array('from_folder' => 'attachments/' . $record->getId(),
                            'to_folder' => 'tmp/attachments/' . $editId,
                            'create_to_folder' => true));
                }
            }

            if ($request->isMethod('POST')) {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    try {
                        $this->get('item_model')->save($record);
                        $fileUploader = $this->get('punk_ave.file_uploader');
                        $fileUploader->syncFiles(
                            array('from_folder' => '/tmp/attachments/' . $editId,
                                'to_folder' => '/attachments/' . $record->getId(),
                                'remove_from_folder' => true,
                                'create_to_folder' => true));
                        $this->get('session')->getFlashBag()->add(
                            'notice',
                            'Ваше объявление успешно добавлено, оно будет опубликовано после одобрения модератором. Спасибо!'
                        );

                        /* blueimp file insert */

                        $files = $fileUploader->getFiles(array('folder' => 'attachments/' . $record->getId()));
                        foreach ($files as $key => $file_name) {
                            $this->get('blueimp_model')->createFile( $record, $file_name );
                        }

                        return $this->redirect($this->generateUrl('app_item_edit',['item_id' => $record->getId()]));
                    } catch (\Exception $e) {
                        $this->get('session')->getFlashBag()->add(
                            'notice',
                            'Your changes were not saved!'.$e->getMessage()
                        );
                    }
                } else {
                    $this->get('session')->getFlashBag()->add(
                        'notice',
                        'Your changes were not saved!'.$form->getErrors()->next()
                    );
                }
            }
            $existingFiles = $this->get('punk_ave.file_uploader')->getFiles(array('folder' => 'tmp/attachments/' . $editId));

            return $this->render('TooBigAppBundle:Item:add_item.html.twig', [
                'rubric' => $rubric,
                'form' => $form->createView(),
                'posting' => $record,
                'editId' => $editId,
                'existingFiles' => $existingFiles,
                'breadcrumbs' => $this->getBreadcrumbs( $rubric ) ]);
        } else {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }


    }

/**
 * @Route("/app/item/{item_id}/edit", name="front_item_edit")
 */
public function editAction($item_id, Request $request)
{
    $record = $this->get('item_model')->getItemById($item_id);
    $rubric = $record->getRubric();
    $form = $this->createForm(
        new ItemForm($this->get('router')),
        $record
    );

    $user = $this->get('security.context')->getToken()->getUser();

    if (is_object($user)) {

        if ( $user === $record->getCreatedBy()){

            /* добавление в форму данных загрузчика фотографий */
            $editId = $request->get('editId');
            if (!preg_match('/^\d+$/', $editId))
            {
                $editId = sprintf('%09d', mt_rand(0, 1999999999));
                if ($record->getId())
                {
                    $this->get('punk_ave.file_uploader')->syncFiles(
                        array('from_folder' => 'attachments/' . $record->getId(),
                            'to_folder' => 'tmp/attachments/' . $editId,
                            'create_to_folder' => true));
                }
            }

            if ($request->isMethod('POST')) {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    try {
                        $this->get('item_model')->save($record);
                        $fileUploader = $this->get('punk_ave.file_uploader');
                        $fileUploader->syncFiles(
                            array('from_folder' => '/tmp/attachments/' . $editId,
                                'to_folder' => '/attachments/' . $record->getId(),
                                'remove_from_folder' => true,
                                'create_to_folder' => true));
                        $this->get('session')->getFlashBag()->add(
                            'notice',
                            'Ваше объявление успешно отредактировано, оно будет опубликовано после одобрения модератором. Спасибо!'
                        );

                        /* blueimp new file insert or update ( delete old files in DB and create new ) */

                        try {
                            $files = $fileUploader->getFiles(array('folder' => 'attachments/' . $record->getId()));
                            if (count( $files ) !== 0) {
                                $this->get('blueimp_model')->deleteItemFiles( $record );
                            }
                            foreach ($files as $key => $file_name) {
                                if (!is_object( $this->get('blueimp_model')->getFileByItemName($record, $file_name) ))
                                    $this->get('blueimp_model')->createFile( $record, $file_name );
                            }
                        } catch (\Exception $e) {
                            $this->setErrors($e->getMessage());
                        }

                        /* make request for watch list updates */

                        try {
                            $this->get('item_subscribtion_model')->updateTime( $record->getId() );
                        } catch (\Exception $e) {
                            $this->setErrors($e->getMessage());
                        }


                    } catch (\Exception $e) {
                        $this->get('session')->getFlashBag()->add(
                            'notice',
                            'Your changes were not saved!'.$e->getMessage()
                        );
                    }
                } else {
                    $this->get('session')->getFlashBag()->add(
                        'notice',
                        'Your changes were not saved! Form validation error!'.$form->getErrors()->next()
                    );
                }
            }
            $existingFiles = $this->get('punk_ave.file_uploader')->getFiles(array('folder' => 'tmp/attachments/' . $editId));
            if ( count($existingFiles) === 0 ) { $existingFiles = $this->get('punk_ave.file_uploader')->getFiles(array('folder' => 'attachments/' . $record->getId())); }

            return $this->render('TooBigAppBundle:Item:edit_item.html.twig', [
                'rubric' => $rubric,
                'form' => $form->createView(),
                'posting' => $record,
                'editId' => $editId,
                'existingFiles' => $existingFiles,
                'breadcrumbs' => $this->getBreadcrumbs( $rubric ) ]);

    } else {
            $this->get('session')->getFlashBag()->add(
                'notice',
                '<div>Данное объявление создано не вами. Желаете создать объявление на основе текущего?</div>
                 <a class="btn btn-success" href="'.$this->generateUrl('app_item_copy', ['item_id' => $record->getId()]).'">Да</a>
                 <a class="btn btn-warning" href="'.$rubric->getFullPath().$record->getSlug().'">Нет</a>'
            );

            /* находим файлы изображения для слайдера, TODO: необходимо заменить на БД запросы */
            $fileUploader = $this->get('punk_ave.file_uploader');
            $files = $fileUploader->getFiles(array('folder' => 'attachments/' . $record->getId()));

            /* находим среднюю оценку по объявлению */
            $rate = $this->get('item_ratecomment_model')->getAvgRateByItem( $record->getId() );
            /* находим опубликованные комментарии по объявлению */
            $comments = $this->get('item_ratecomment_model')->getCommentsByItem( $record->getId() );

            $response = array(
                'content' => $record,
                'files' => $files,
                'rate' => $rate,
                'comments' => $comments,
                'breadcrumbs' => $this->getBreadcrumbs( $rubric ) );

            $user = $this->get('security.context')->getToken()->getUser();
            if ( is_object( $user ) && $user !== $record->getCreatedBy()) {
                /* находим следит ли пользователь за объявлением, если оно ему не принадлежит */
                $watch = $this->get('item_subscribtion_model')->getWatchByItem($record->getId());
                $response['watch_item'] = $watch;
                /* находим комментировал ли пользователь объявление, и если оно ему не принадлежит */
                $rate_comment = $this->get('item_ratecomment_model')->getRateCommentByItem($record->getId());
                $response['rate_comment_item'] = $rate_comment;
                /* обновляем счетчик посещений объявления, если оно ему не принадлежит */
                $this->get('item_model')->updateHits( $record );
            }

            return $this->render( 'TooBigAppBundle:Item:item.html.twig', $response );
        }

    } else {
        return $this->redirect($this->generateUrl('fos_user_security_login'));
    }
}

/**
 * @param $item_id
 * response JsonResponse
 */
public function watchAction( $item_id )
{
    $response = new JsonResponse();
    $watch = $this->get('item_subscribtion_model')->watch($item_id);
    $watch_id = $watch->getId();
    if (is_int( $watch_id )){
        $response->setData(array(
            'path' => $this->generateUrl('app_item_unwatch', array('item_id' => $item_id)),
            'caption' => 'Отписаться от обновлений'
        ));
    } else {
        $response->setData(array(
            'path' => $this->generateUrl('app_item_watch', array('item_id' => $item_id)),
            'error' => 'Невозможно выполнить операцию, повторите позже.',
            'caption' => 'Подписаться на обновления'
        ));
    }

    return $response;
}

/**
 * @param $item_id
 * response JsonResponse
 */
public function unwatchAction( $item_id )
{
    $response = new JsonResponse();
    $watch = $this->get('item_subscribtion_model')->unwatch($item_id);
    if ( null !== $watch ){
        $response->setData(array(
            'path' => $this->generateUrl('app_item_unwatch', array('item_id' => $item_id)),
            'error' => 'Невозможно выполнить операцию, повторите позже.',
            'caption' => 'Отписаться от обновлений'
        ));
    } else {
        $response->setData(array(
            'path' => $this->generateUrl('app_item_watch', array('item_id' => $item_id)),
            'caption' => 'Подписаться на обновления'
        ));
    }

    return $response;
}
/**
 * @Route("/app/item/{item_id}/copy", name="app_item_copy")
 */
public function copyAction($item_id, Request $request)
{
    $record = $this->get('item_model')->getItemById($item_id);
    $copy = $this->get('item_model')->makeCopy($record);
    $rubric = $copy->getRubric();

    $user = $this->get('security.context')->getToken()->getUser();

    if (is_object($user)) {

        $form = $this->createForm(
            new ItemForm($this->get('router')),
            $copy
        );

        /* добавление в форму данных загрузчика фотографий */
        $editId = $request->get('editId');
        if (!preg_match('/^\d+$/', $editId))
        {
            $editId = sprintf('%09d', mt_rand(0, 1999999999));
        }

        return $this->render('TooBigAppBundle:Item:add_item.html.twig', [
            'rubric' => $rubric,
            'form' => $form->createView(),
            'posting' => $copy,
            'editId' => $editId,
            'breadcrumbs' => $this->getBreadcrumbs( $rubric )
        ]);

    } else {
        return $this->redirect($this->generateUrl('fos_user_security_login'));
    }
}

/**
 *
 * @Route("/app/file/upload", name="app_file_upload")
 * @Template()
 */
public function uploadAction(Request $request)
{
    $editId = $request->get('editId');
    if (!preg_match('/^\d+$/', $editId))
    {
        throw new Exception('Bad edit id');
    }

    $this->get('punk_ave.file_uploader')->handleFileUpload([
        'folder' => 'tmp/attachments/' . $editId,
        'allowed_extensions' => array('jpeg', 'jpg', 'png', 'gif')
    ]);
}

    /**
     * @Template("TooBigAppBundle:Item:item.html.twig")
     */
    public function indexAction()
    {
        $content = $this->getRubricIndex($this->getCurrentRubric());

        if ($content && !$content->getEnabled()) $content = null;
        //if (!$content) throw $this->createNotFoundException('Индексный материал не найден');

        return   array('content' => $content);
    }

    /**
     * @Template()
     * @param Request $request
     */
    public function listAction(Request $request)
    {
        $rubric = $this->getCurrentRubric();

        $filter_params = [];
        $price_params = [];
        $filter_params['Brand'] = isset($request->request->get('ItemsFilter')['brand']) ? $request->request->get('ItemsFilter')['brand'] : null;
        $filter_params['Size'] = isset($request->request->get('ItemsFilter')['size']) ? $request->request->get('ItemsFilter')['size'] : null;
        $filter_params['Color'] = isset($request->request->get('ItemsFilter')['color']) ? $request->request->get('ItemsFilter')['color'] : null;
        $filter_params['Gender'] = isset($request->request->get('ItemsFilter')['gender']) ? $request->request->get('ItemsFilter')['gender'] : null;
        $min = $this->get('rubric_model')->getRubricPriceRange($rubric, $filter_params, 'min');
        $max = $this->get('rubric_model')->getRubricPriceRange($rubric, $filter_params, 'max');
        $price_params['Min'] = $request->request->get('ItemsFilter')['price_min'] ? : $min[0][1];
        $price_params['Max'] = $request->request->get('ItemsFilter')['price_max'] ? : $max[0][1];

        $query = $this->getDoctrine()
            ->getRepository('TooBigAppBundle:Item')
            ->createQuery('c', function ($qb) use ($rubric, $filter_params, $price_params)
        {
            $qb->fromRubric($rubric)->whereEnabled()->whereIndex(false)->withSubrubrics(true);
            foreach ($filter_params as $key => $value) {
                if ( null !== $value ){
                    $qb_func = 'where'.$key;
                    $qb->$qb_func($value);
                }
            }
            $qb->andWhere($qb->expr()->between('c.price', $price_params['Min'], $price_params['Max']));
            $qb->addOrderBy ('c.date','DESC')->addOrderBy ('c.updatedAt','DESC');
        });

        $filterForm = $this->createForm( new ItemsFilterType($this->get('router')) );
        if ($request->isMethod('GET')) {
            $filterForm->handleRequest($request);
        }

        return array(
            'entities' => $this->paginate($query, 20),
            'breadcrumbs' => $this->getBreadcrumbs( $rubric ),
            'filterForm' => $filterForm->createView(),
            'rubricPriceRange' => $price_params,
        );
    }

    /**
     * @Template("TooBigAppBundle:Item:item.html.twig")
     */
    public function contentBySlugAction($slug)
    {
        $rubric = $this->getCurrentRubric();
        $content = $this->getDoctrine()
            ->getRepository('TooBigAppBundle:Item')
            ->createQuery('c', function ($qb) use ($rubric, $slug)
        {
            $qb->fromRubric($rubric)->whereSlug($slug)->whereEnabled();
        })->getOneOrNullResult();

        if (!$content) throw $this->createNotFoundException('Объявление с кодом "' . $slug . '" не найдено');

        if ($content->getRedirectUrl())
            return $this->redirect($content->getRedirectUrl());

        /* находим файлы изображения для слайдера, TODO: необходимо заменить на БД запросы */
        $fileUploader = $this->get('punk_ave.file_uploader');
        $files = $fileUploader->getFiles(array('folder' => 'attachments/' . $content->getId()));
        /* находим среднюю оценку по объявлению */
        $rate = $this->get('item_ratecomment_model')->getAvgRateByItem( $content->getId() );
        /* находим опубликованные комментарии по объявлению */
        $comments = $this->get('item_ratecomment_model')->getCommentsByItem( $content->getId() );


        $response = array(
            'content' => $content,
            'files' => $files,
            'rate' => $rate,
            'comments' => $comments,
            'breadcrumbs' => $this->getBreadcrumbs( $rubric )
        );

            $user = $this->get('security.context')->getToken()->getUser();
            if ( is_object( $user ) && $user !== $content->getCreatedBy()) {
                /* находим следит ли пользователь за объявлением, если оно ему не принадлежит */
                $watch = $this->get('item_subscribtion_model')->getWatchByItem($content->getId());
                $response['watch_item'] = $watch;
                /* находим комментировал ли пользователь объявление, и если оно ему не принадлежит */
                $rate_comment = $this->get('item_ratecomment_model')->getRateCommentByItem($content->getId());
                $response['rate_comment_item'] = $rate_comment;
                /* обновляем счетчик посещений объявления, если оно ему не принадлежит */
                $this->get('item_model')->updateHits( $content );
            }

        return $response;

    }

    /**
     * @param $item_id
     * response JsonResponse
     */
    public function addcommentAction( $item_id, Request $request )
    {
        $response = new JsonResponse();

        $rate_comment = new RateComment();
        $form = $this->createForm(
            new RateCommentType( $this->get('router'), $item_id ),
            $rate_comment
        );

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $this->get('item_ratecomment_model')->save( $rate_comment, $item_id );
                    $response->setData(array(
                        'message' => 'Благодарим вас за ваш комментарий и вашу оценку, оно будет опубликовано после одобрения модератором.'
                    ));
                } catch (\Exception $e) {
                    return $this->render('TooBigAppBundle:Item:add_rate_comment.html.twig', [ 'form' => $form->createView(), 'item_id' => $item_id ]);
                }
            } else {
                try {
                    return $this->render('TooBigAppBundle:Item:add_rate_comment.html.twig', [ 'form' => $form->createView(), 'item_id' => $item_id ]);
                } catch (\Exception $e) {

                }
            }
        }
        return $response;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $error
     */
    public function setErrors($error)
    {
        $this->errors[] = $error;
    }

    /**
     * @param Rubric $rubric
     */
    protected function getBreadcrumbs(Rubric $rubric){
        return array_reverse( $this->get('rubric_model')->getParentRubrics($rubric->getId(), []) );
    }
}
