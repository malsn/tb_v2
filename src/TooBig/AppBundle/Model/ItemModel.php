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
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($record);
        $em->flush();
    }

    /**
     * @param int $item_id
     */
    public function watch( $item_id ){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $record = $this->getItemById($item_id);
        $watch_item = new ItemSubscribtion();
        $watch_item->setUser($user);
        $watch_item->setItem($record);
        $watch_item->setCreatedAt(new \DateTime());
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($watch_item);
        $em->flush();

        return $watch_item->getId();
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
}