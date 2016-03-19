<?php

namespace TooBig\AppBundle\Controller;

use Application\Iphp\CoreBundle\Entity\Rubric;
use Symfony\Component\Debug\ExceptionHandler;
use TooBig\AppBundle\Entity\Item;
use Iphp\ContentBundle\Controller\ContentController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use TooBig\AppBundle\Entity\RateComment;
use TooBig\AppBundle\Form\Type\CaptchaForm;
use TooBig\AppBundle\Form\ItemForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TooBig\AppBundle\Form\Type\ItemEditForm;
use TooBig\AppBundle\Form\Type\ItemsFilterType;
use TooBig\AppBundle\Form\Type\RateCommentType;
use TooBig\AppBundle\Model\ItemSubscribtionModel;
use Iphp\CoreBundle\Controller\RubricAwareController;
use Application\Sonata\UserBundle\Controller\SecurityFOSUser1Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ItemController extends RubricAwareController
{
    protected $errors;

    public function brandItemsListAction (){

    }

    /**
     * @Template("TooBigAppBundle:Brand:list.html.twig")
     */
    public function brandListAction(){

        $this->setReturnUrl();

        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT p
            FROM TooBigAppBundle:Brand p
            ORDER BY p.name ASC'
        );

        return array(
            'entities' => $this->paginate($query, 20)
        );
    }

    /**
     * @Route("/", name="app_main")
     * @Template("TooBigAppBundle::page-main-with-list.html.twig")
     */
    public function siteIndexAction(Request $request){

        /* redirect after success authentication if it happened */
        $user = $this->get('security.context')->getToken()->getUser();
        if (is_object($user) && isset($_SESSION['return_url'])) {
            $redirect_url = $_SESSION['return_url'];
            $this->unsetReturnUrl();
            return new RedirectResponse($redirect_url);
        }

        $rubric = $this->get('rubric_model')->getRubricById(1);

        $filter_params = [];
        $price_params = [];
        $filter_params['Brand'] = isset($request->query->get('ItemsFilter')['brand']) ? $request->query->get('ItemsFilter')['brand'] : null;
        $filter_params['Size'] = isset($request->query->get('ItemsFilter')['size']) ? $request->query->get('ItemsFilter')['size'] : null;
        $filter_params['Color'] = isset($request->query->get('ItemsFilter')['color']) ? $request->query->get('ItemsFilter')['color'] : null;
        $filter_params['Gender'] = isset($request->query->get('ItemsFilter')['gender']) ? $request->query->get('ItemsFilter')['gender'] : null;
        $search_params['Search'] = isset($request->query->get('ItemsFilter')['search']) ? $request->query->get('ItemsFilter')['search'] : null;
        /*$min = $this->get('rubric_model')->getRubricPriceRange($rubric, $filter_params, 'min');
        $max = $this->get('rubric_model')->getRubricPriceRange($rubric, $filter_params, 'max');
        $price_params['Min'] = $request->query->get('ItemsFilter')['price_min'] ? : $min[0][1];
        $price_params['Max'] = $request->query->get('ItemsFilter')['price_max'] ? : $max[0][1];*/

        $query = $this->itemsQueryBuilder($rubric, $filter_params, $price_params, $search_params);
        $query_filter = $this->itemsQueryBuilder($rubric, $filter_params, $price_params, $search_params);

        $query_non_filter = $this->getDoctrine()
            ->getRepository('TooBigAppBundle:Item')
            ->createQuery('c', function ($qb) use ($rubric)
            {
                $qb->fromRubric($rubric)->withSubrubrics(true);
                $qb->whereIndex(false);
                $qb->whereEnabled();
            });

        /* получаем фильтр от всего результата $query_non_filter */
        $filters = $this->get('item_model')->getItemsFilter($query_non_filter);

        $filterForm = $this->createForm( new ItemsFilterType($this->get('router'),$filters) );
        if ($request->isMethod('GET')) {
            $filterForm->handleRequest($request);
        }

        //if ( $request->query->count() ) {
            return array(
                'entities' => $this->paginate($query, 20),
                'filterForm' => $filterForm->createView(),
                'rubricPriceRange' => $price_params,
                'filter_params'=>$filter_params,
                'count' => count($query_filter->getResult()),
                'filter_results' => $filters,
            );
        /*} else {
            return array(
                'filterForm' => $filterForm->createView(),
                'rubricPriceRange' => $price_params,
            );
        }*/

    }

    /**
     * @Route("/app/item/add", name="app_item_add_common")
     */
    public function addCommonAction(){

        $user = $this->get('security.context')->getToken()->getUser();

        if (!is_object($user)) {

           return $this->getUserLoginForm();

        } else {
            $rubric = $this->get('rubric_model')->getRubricById(1);
            return $this->render('TooBigAppBundle:Rubric:select_rubric.html.twig', [
                'rubric' => $rubric
            ]);
        }
    }

    public function listRubricChildrenAction( Request $request ){

        $rubric = $this->get('rubric_model')->getRubricById( $request->request->get('rubric') );
        $struct = $request->request->get('struct');
        $breadcrumbs = [];
        if (null !== $rubric){
            $rubric_children = $rubric->getChildren();
            try {
                if ($request->request->get('rubric') != 1) {
                    $breadcrumbs = $this->getBreadcrumbs($rubric);
                }
            } catch (\Exception $e) {

            }

            return $this->render('TooBigAppBundle:Rubric:rubric_children.html.twig', [
                'rubric'=>$rubric,
                'breadcrumbs'=>$breadcrumbs,
                'rubrics'=>$rubric_children,
                'struct'=>$struct
            ]);
        } else {
            return false;
        }
    }

    /**
     * @Route("/app/item/add/{rubric_id}", name="app_item_add")
     */
    public function addAction($rubric_id, Request $request)
    {
        $rubric = $this->get('rubric_model')->getRubricById($rubric_id);

        $record = new Item();
        $record->setRubric($rubric);
        $record->setHits(0);

        $user = $this->get('security.context')->getToken()->getUser();

        if (!is_object($user)) {

           return $this->getUserLoginForm();

        } else {

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
                        $this->get('flash_bag')->addMessage('Ваше объявление успешно добавлено, оно будет опубликовано после одобрения модератором. Спасибо!');

                        /* blueimp file insert */

                        $files = $fileUploader->getFiles(array('folder' => 'attachments/' . $record->getId()));
                        foreach ($files as $key => $file_name) {
                            $this->get('blueimp_model')->createFile( $record, $file_name );
                        }

                        return $this->redirect($this->generateUrl('app_item_edit',['item_id' => $record->getId()]));
                    } catch (\Exception $e) {
                        $this->get('flash_bag')->addMessage('Произошла ошибка в добавлении объявления! '.$e->getMessage());
                    }
                } else {
                    $this->get('flash_bag')->addMessage('Произошла ошибка в добавлении объявления! '.$form->getErrors()->next());
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
        new ItemEditForm($this->get('router')),
        $record
    );

    $user = $this->get('security.context')->getToken()->getUser();

    if (!is_object($user)) {

       return $this->getUserLoginForm();

    } else {

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
                if ($form->isValid() && $form->isSubmitted()) {
                    try {
                        $this->get('item_model')->save($record);
                        $fileUploader = $this->get('punk_ave.file_uploader');
                        $fileUploader->syncFiles(
                            array('from_folder' => '/tmp/attachments/' . $editId,
                                'to_folder' => '/attachments/' . $record->getId(),
                                'remove_from_folder' => true,
                                'create_to_folder' => true));
                        $this->get('flash_bag')->addMessage('Ваше объявление успешно отредактировано, оно будет опубликовано после одобрения модератором. Спасибо!');

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
                        $this->get('flash_bag')->addMessage('Произошла ошибка при сохранении объявления! '.$e->getMessage());
                    }
                } else {
                    $this->get('flash_bag')->addMessage('Произошла ошибка при сохранении объявления! Ошибка валидации формы! '.$form->getErrors()->next());
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
            $this->get('flash_bag')->addMessage('<div>Данное объявление создано не вами. Желаете создать объявление на основе текущего?</div><div class="form-group control-group"><div class="col-sm-12 form-buttons center"><a class="btn btn-success" href="'.$this->generateUrl('app_item_copy', ['item_id' => $record->getId()]).'">Да</a><a class="btn btn-warning" href="'.$rubric->getFullPath().$record->getSlug().'">Нет</a></div></div>');

            /* находим файлы изображения для слайдера, TODO: необходимо заменить на БД запросы */
            $fileUploader = $this->get('punk_ave.file_uploader');
            $files = $fileUploader->getFiles(array('folder' => 'attachments/' . $record->getId()));

            /* находим опубликованные комментарии по объявлению */
            $comments = $this->get('item_ratecomment_model')->getCommentsByItem( $record->getId() );

            $response = array(
                'content' => $record,
                'files' => $files,
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
            'caption' => "<i class='glyphicon glyphicon-star'></i>Отписаться от обновлений"
        ));
    } else {
        $response->setData(array(
            'path' => $this->generateUrl('app_item_watch', array('item_id' => $item_id)),
            'error' => 'Невозможно выполнить операцию, повторите позже.',
            'caption' => "<i class='glyphicon glyphicon-star-empty'></i>Подписаться на обновления"
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
            'caption' => "<i class='glyphicon glyphicon-star'></i>Отписаться от обновлений"
        ));
    } else {
        $response->setData(array(
            'path' => $this->generateUrl('app_item_watch', array('item_id' => $item_id)),
            'caption' =>  "<i class='glyphicon glyphicon-star-empty'></i>Подписаться на обновления"
        ));
    }

    return $response;
}

    /**
     * @Template("TooBigAppBundle:Item:watch_status.html.twig")
     * @param $item_id
     * @return array
     */
    public function watchStatusAction($item_id){
        $response = array();
        $user = $this->get('security.context')->getToken()->getUser();
        $content = $this->getDoctrine()
            ->getRepository('TooBigAppBundle:Item')->find($item_id);

        if (!$content) { throw $this->createNotFoundException('Объявление с идентификатором "' . $item_id . '" не найдено'); }
        if ( is_object( $user ) && $user !== $content->getCreatedBy()) {
            $watch = $this->get('item_subscribtion_model')->getWatchByItem($content->getId());
            $response = array(
                'content' => $content,
                'watch_item' => $watch
            );
        }
        return $response;
    }

    /**
     * @Template("TooBigAppBundle:Item:item_rating.html.twig")
     * @param $item_id
     * @return array
     */
    public function getItemRatingAction($item_id){
        $content = $this->getDoctrine()
            ->getRepository('TooBigAppBundle:Item')->find($item_id);

        if (!$content) { throw $this->createNotFoundException('Объявление с идентификатором "' . $item_id . '" не найдено'); }
        $rate = $this->get('item_ratecomment_model')->getAvgRateByItem( $content->getId() );
        /* находим опубликованные комментарии по объявлению */
        $comments = $this->get('item_ratecomment_model')->getCommentsByItem( $content->getId() );
        $response = array(
            'rate' => $rate,
            'comments' => $comments
        );
        return $response;
    }

    /**
     * @Route("/app/item/{item_id}/copy", name="app_item_copy")
     * @param $item_id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
public function copyAction($item_id, Request $request)
{
    $record = $this->get('item_model')->getItemById($item_id);
    $copy = $this->get('item_model')->makeCopy($record);
    $rubric = $copy->getRubric();

    $user = $this->get('security.context')->getToken()->getUser();

    if (!is_object($user)) {

        return $this->getUserLoginForm();

    } else {

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
    $this->setReturnUrl();

    $content = $this->getRubricIndex($this->getCurrentRubric());

    if ($content && !$content->getEnabled()) $content = null;
    //if (!$content) throw $this->createNotFoundException('Индексный материал не найден');

    return   array('content' => $content);
}

/**
 * @Template()
 * @param Request $request
 * @return array
 */
public function listAction(Request $request)
{
    $this->setReturnUrl();

    $rubric = $this->getCurrentRubric();

    $filter_params = [];
    $price_params = [];
    $filter_params['Brand'] = isset($request->query->get('ItemsFilter')['brand']) ? $request->query->get('ItemsFilter')['brand'] : null;
    $filter_params['Size'] = isset($request->query->get('ItemsFilter')['size']) ? $request->query->get('ItemsFilter')['size'] : null;
    $filter_params['Color'] = isset($request->query->get('ItemsFilter')['color']) ? $request->query->get('ItemsFilter')['color'] : null;
    $filter_params['Gender'] = isset($request->query->get('ItemsFilter')['gender']) ? $request->query->get('ItemsFilter')['gender'] : null;
    $search_params['Search'] = isset($request->query->get('ItemsFilter')['search']) ? $request->query->get('ItemsFilter')['search'] : null;
    /*$min = $this->get('rubric_model')->getRubricPriceRange($rubric, $filter_params, 'min');
    $max = $this->get('rubric_model')->getRubricPriceRange($rubric, $filter_params, 'max');
    $price_params['Min'] = $request->query->get('ItemsFilter')['price_min'] ? : $min[0][1];
    $price_params['Max'] = $request->query->get('ItemsFilter')['price_max'] ? : $max[0][1];*/

    $query = $this->itemsQueryBuilder($rubric, $filter_params, $price_params, $search_params);
    $query_filter = $this->itemsQueryBuilder($rubric, $filter_params, $price_params, $search_params);

    $query_non_filter = $this->getDoctrine()
        ->getRepository('TooBigAppBundle:Item')
        ->createQuery('c', function ($qb) use ($rubric)
        {
            $qb->fromRubric($rubric)->withSubrubrics(true);
            $qb->whereIndex(false);
            $qb->whereEnabled();
        });

    /* получаем фильтр от всего результата $query_non_filter */
    $filters = $this->get('item_model')->getItemsFilter($query_non_filter);

    $filterForm = $this->createForm( new ItemsFilterType($this->get('router'),$filters) );
    if ($request->isMethod('GET')) {
        $filterForm->handleRequest($request);
    }

    return array(
        'entities' => $this->paginate($query, 20),
        'breadcrumbs' => $this->getBreadcrumbs( $rubric ),
        'filterForm' => $filterForm->createView(),
        'rubricPriceRange' => $price_params,
        'filter_params'=>$filter_params,
        'count' => count($query_filter->getResult()),
        'filter_results' => $filters
    );
}

/**
 * @Template("TooBigAppBundle:Item:item.html.twig")
 */
public function contentBySlugAction($slug)
{
    $this->setReturnUrl();

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
    /* находим опубликованные комментарии по объявлению */
    $comments = $this->get('item_ratecomment_model')->getCommentsByItem( $content->getId() );

    $response = array(
        'content' => $content,
        'files' => $files,
        'comments' => $comments,
        'breadcrumbs' => $this->getBreadcrumbs( $rubric )
    );

        $user = $this->get('security.context')->getToken()->getUser();
        if ( is_object( $user ) && $user !== $content->getCreatedBy()) {
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
                    'message' => 'Благодарим вас за ваш комментарий и вашу оценку, он будет опубликован после проверки модератором.'
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
 * @param Request $request
 * @param $item_id
 * @return Response
 */
public function viewItemPhoneAction(Request $request, $item_id){
    $record = $this->get('item_model')->getItemById($item_id);

    $form = $this->createForm(
        new CaptchaForm()
    );

    if ($request->isMethod('POST')){
        $form->handleRequest($request);
        if ($form->isValid()) {
            return new Response ($record->getPhone());
        }
    }

    return $this->render('TooBigAppBundle:Captcha:form.html.twig', array(
        'form' => $form->createView()
    ));
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
 * @return array
 */
public function getBreadcrumbs(Rubric $rubric){
    return array_reverse( $this->get('rubric_model')->getParentRubrics($rubric->getId(), []) );
}

protected function itemsQueryBuilder ($rubric, $filter_params, $price_params, $search_params){
    return $this->getDoctrine()
        ->getRepository('TooBigAppBundle:Item')
        ->createQuery('c', function ($qb) use ($rubric, $filter_params, $price_params, $search_params)
        {
            $qb->fromRubric($rubric)->withSubrubrics(true);
            foreach ($filter_params as $key => $value) {
                if ( null !== $value ){
                    $qb_func = 'where'.$key;
                    $qb->$qb_func($value);
                }
            }
            /*if (null !== $price_params['Min'] && null !== $price_params['Max']) {
                $qb->andWhere($qb->expr()->between('c.price', $price_params['Min'], $price_params['Max']));
            }*/
            if (null !== $search_params['Search']) {
                $search_words = preg_split("/[\s,]+/", $search_params['Search']);
                if (count($search_words)){
                    $orX = $qb->expr()->orX();
                    foreach ($search_words as $word) {
                        if (strlen($word) > 2) {
                            $orX->add($qb->expr()->like('c.content', "'%".$word."%'"));
                            $orX->add($qb->expr()->like('c.title', "'%".$word."%'"));
                        }
                    }
                    $qb->andWhere($orX);
                }
            }
            $qb->whereIndex(false);
            $qb->whereEnabled();
            $qb->addOrderBy ('c.date','DESC')->addOrderBy ('c.updatedAt','DESC');
        });
}

protected function getUserLoginForm(){
    $resp_login = $this->forward('ApplicationSonataUserBundle:SecurityFOSUser1:loginForm');
    $resp_login = preg_replace('/[\r\n]/i','',$resp_login->getContent());
    $this->setReturnUrl();
    $this->get('flash_bag')->addMessage(
        $resp_login
    );
    return $this->forward('TooBigAppBundle:Item:siteIndex');
}

protected function setReturnUrl(){
    $user = $this->get('security.context')->getToken()->getUser();
    if (!is_object($user)) {
        $_SESSION['return_url'] = $this->get('request_stack')->getMasterRequest()->server->get('REQUEST_URI');
    }
}

protected function unsetReturnUrl(){
    unset($_SESSION['return_url']);
}

}
