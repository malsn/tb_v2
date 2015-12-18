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

        $isNew = true; //предварительно это нова€ форма

        if ($request->isMethod('POST')) {
            $isNew = false; //тип сменилс€ на отправленную форму
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
                        'Your changes were saved!'
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
        return $this->render('TooBigAppBundle:Item:add_item.html.twig', [
            'rubric' => $rubric,
            'form' => $form->createView(),
            'posting' => $record,
            'editId' => $editId,
            'isNew' => $isNew,
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
        //if (!$content) throw $this->createNotFoundException('»ндексный материал не найден');

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

        if (!$content) throw $this->createNotFoundException('ќбъ€вление с кодом "' . $slug . '" не найдено');

        if ($content->getRedirectUrl())
            return $this->redirect($content->getRedirectUrl());


        return   array('content' => $content);

    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository('TooBigAppBundle:Item');
    }
}
