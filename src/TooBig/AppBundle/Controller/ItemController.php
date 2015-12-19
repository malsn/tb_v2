<?php

namespace TooBig\AppBundle\Controller;

use TooBig\AppBundle\Entity\Item;
use Iphp\ContentBundle\Controller\ContentController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TooBig\AppBundle\Form\ItemForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ItemController extends ContentController
{
    /**
     * @Route("/app/item/add/{rubric_id}", name="front_item_add")
     */
    public function addAction($rubric_id, Request $request)
    {
        $rubric = $this->get('rubric_model')->getRubricById($rubric_id);

        $record = new Item();
        $record->setRubric($rubric);
        $record->setEnabled(true);

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
                    return $this->forward('TooBigAppBundle:Item:list');
                    //return $this->render('TooBigAppBundle:Item:item.html.twig', ['content'=>$record]);
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add(
                        'notice',
                        'Your changes were not saved!'
                    );
                }
            } else {
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Your changes were not saved!'
                );
            }
        }
        $existingFiles = $this->get('punk_ave.file_uploader')->getFiles(array('folder' => 'tmp/attachments/' . $editId));
        return $this->render('TooBigAppBundle:Item:add_item.html.twig', [
            'rubric' => $rubric,
            'form' => $form->createView(),
            'posting' => $record,
            'editId' => $editId,
            'existingFiles' => $existingFiles ]);
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
                return $this->render('TooBigAppBundle:Item:item.html.twig', ['content'=>$record]);
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Your changes were not saved!'
                );
            }
        } else {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your changes were not saved!'
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
        'existingFiles' => $existingFiles ]);
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
        throw new Exception("Bad edit id");
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
     */
    public function listAction()
    {
        $rubric = $this->getCurrentRubric();

        $query = $this->getRepository()->createQuery('c', function ($qb) use ($rubric)
        {
            $qb->fromRubric($rubric)->whereEnabled()->whereIndex(false)->withSubrubrics(true)
                ->addOrderBy ('c.date','DESC')->addOrderBy ('c.updatedAt','DESC');
        });


        return  array('entities' => $this->paginate($query, 20));
    }

    /**
     * @Template("TooBigAppBundle:Item:item.html.twig")
     */
    public function contentBySlugAction($slug)
    {
        $rubric = $this->getCurrentRubric();
        $content = $this->getRepository()->createQuery('c', function ($qb) use ($rubric, $slug)
        {
            $qb->fromRubric($rubric)->whereSlug($slug)->whereEnabled();
        })->getOneOrNullResult();

        if (!$content) throw $this->createNotFoundException('Объявление с кодом "' . $slug . '" не найдено');

        if ($content->getRedirectUrl())
            return $this->redirect($content->getRedirectUrl());

        $fileUploader = $this->get('punk_ave.file_uploader');
        $files = $fileUploader->getFiles(array('folder' => 'attachments/' . $content->getId()));

        return   array('content' => $content, 'files' => $files);

    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository('TooBigAppBundle:Item');
    }
}
