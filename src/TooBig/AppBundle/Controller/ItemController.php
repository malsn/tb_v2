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
     * @Route("/item/add/{rubric_id}", name="front_item_add")
     */
    public function addAction($rubric_id, Request $request)
    {
        $rubric = $this->get('rubric_model')->getRubricById($rubric_id);

        $record = new Item();
        $record->setRubric($rubric);
        $record->setEnabled(true);

        $form = $this->createForm(
            new ItemForm(),
            $record
        );

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $this->get('item_model')->save($record);
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
        return $this->render('TooBigAppBundle:Item:add_item.html.twig', ['rubric' => $rubric, 'form' => $form->createView() ]);
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
