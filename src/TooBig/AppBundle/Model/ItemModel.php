<?php

namespace TooBig\AppBundle\Model;

use Sonata\UserBundle\Model\UserInterface;
use TooBig\AppBundle\Entity\Item;
use Application\Iphp\CoreBundle\Entity\Rubric;
use Symfony\Component\DependencyInjection\ContainerAware;

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
     * @param $user
     * @return array
     */
    public function getItemsByUser(UserInterface $user){
        $items = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:Item')
            ->findBy(
                ['createdBy'=>$user],
                ['createdAt'=>'DESC']
            );
        return $items;
    }
}