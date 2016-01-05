<?php

namespace TooBig\AppBundle\Model;

use Sonata\UserBundle\Model\UserInterface;
use TooBig\AppBundle\Entity\ItemSubscribtion;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class ItemSubscribtionModel
 * @package TooBig\AppBundle\Model
 */
class ItemSubscribtionModel extends ContainerAware {

    /**
     * @param int $item_id
     * return ItemSubscribtion
     */
    public function watch( $item_id ){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $record = $this->container->get('item_model')->getItemById($item_id);
        $watch_item = new ItemSubscribtion();
        $watch_item->setUser($user);
        $watch_item->setItem($record);
        $watch_item->setCreatedAt(new \DateTime());
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($watch_item);
        $em->flush();

        return $this->getWatchByItem( $item_id );
    }

    /**
     * @param int $item_id
     * return ItemSubscribtion
     */
    public function unwatch( $item_id ){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->container->get('doctrine.orm.entity_manager');
        $query = $em->createQuery(
        'DELETE
         FROM TooBigAppBundle:ItemSubscribtion its
         WHERE its.item = :item_id AND its.user = :user_id')
            ->setParameter('item_id', $item_id)
            ->setParameter('user_id', $user->getId());
        $query->getResult();

        return $this->getWatchByItem( $item_id );
    }

    /**
     * @param $item_id
     * @return ItemSubscribtion
     */
    public function getWatchByItem($item_id){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $watch = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:ItemSubscribtion')
            ->findOneBy([ 'item' => $item_id, 'user' => $user->getId()]);
        return $watch;
    }
}