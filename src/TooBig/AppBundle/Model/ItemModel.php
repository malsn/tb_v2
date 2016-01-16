<?php

namespace TooBig\AppBundle\Model;

use Sonata\UserBundle\Model\UserInterface;
use TooBig\AppBundle\Entity\Item;
use Application\Iphp\CoreBundle\Entity\Rubric;
use Symfony\Component\DependencyInjection\ContainerAware;
use TooBig\AppBundle\Entity\ItemSubscribtion;

/**
 * Class ItemModel
 * @package TooBig\AppBundle\Model
 */
class ItemModel extends ContainerAware {
    /**
     * @param Item $record
     */
    public function save(Item $record){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $record->setCreatedBy($user);
        $record->setUpdatedBy($user);
        $record->setEnabled(false);

        $pub_date = new \DateTime();
        $pub_date->add(new \DateInterval('P30D'));

        $record->setPublicationDateEnd($pub_date);
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($record);
        $em->flush();
    }

    /**
     * @param Item $record
     * @return Item
     */
    public function makeCopy(Item $record){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $copy = new Item();
        $copy->setCreatedBy($user);
        $copy->setUpdatedBy($user);
        $copy->setCreatedAt(new \DateTime());
        $copy->setEnabled(false);
        $copy->setTitle($record->getTitle());
        $copy->setRubric($record->getRubric());
        $copy->setColor($record->getColor());
        $copy->setAbstract($record->getAbstract());
        $copy->setContent($record->getContent());
        $copy->setGender($record->getGender());
        $copy->setBrand($record->getBrand());
        $copy->setModel($record->getModel());
        $copy->setSizeType($record->getSizeType());
        $copy->setSize($record->getSize());
        $copy->setHits(0);
        return $copy;
    }
    /**
     * @param Item $record
     * @return Item
     */
    public function makeFixtureCopy(Item $record){
        $copy = new Item();
        $copy->setCreatedBy($record->getCreatedBy());
        $copy->setUpdatedBy($record->getUpdatedBy());
        $copy->setCreatedAt(new \DateTime());
        $copy->setEnabled(true);
        $copy->setTitle($record->getTitle());
        $copy->setRubric($record->getRubric());
        $copy->setColor($record->getColor());
        $copy->setAbstract($record->getAbstract());
        $copy->setContent($record->getContent());
        $copy->setGender($record->getGender());
        $copy->setBrand($record->getBrand());
        $copy->setModel($record->getModel());
        $copy->setSizeType($record->getSizeType());
        $copy->setSize($record->getSize());
        $copy->setHits(0);
        return $copy;
    }

    /**
     * @param $item_id
     * @return mixed
     */
    public function getItemById($item_id){
        $item = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:Item')
            ->find($item_id);
        return $item;
    }

    /**
     * @return array
     */
    public function getItemsByUser(){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $items = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:Item')
            ->findBy(
                ['createdBy'=>$user],
                ['createdAt'=>'DESC']
            );
        return $items;
    }

    /**
     * @param Item $record
     */
    public function updateHits(Item $record){
        $record->setHits( $record->getHits()+1 );
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($record);
        $em->flush();
    }
}