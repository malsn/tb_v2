<?php

namespace TooBig\AppBundle\Model;

use TooBig\AppBundle\Entity\Item;
use TooBig\AppBundle\Entity\ItemBlueimp;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class BlueimpModel
 * @package TooBig\AppBundle\Model
 */
class BlueimpModel extends ContainerAware {
    /**
     * @param Item $record
     * @param $name
     */
    public function createFile( Item $record, $name ){
        //$user = $this->container->get('security.context')->getToken()->getUser();
        $file = new ItemBlueimp();
        $file->setItem($record);
        $file->setCreatedAt((new \DateTime));
        $file->setName( $name );
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($file);
        $em->flush();
    }

    /**
     * @param Item $item
     */
    public function deleteItemFiles( Item $item ){
        $em = $this->container->get('doctrine.orm.entity_manager');
        $query = $em->createQuery(
        'DELETE
         FROM TooBigAppBundle:ItemBlueimp ib
         WHERE ib.item = :item_id')->setParameter('item_id', $item->getId());
        return $query->getResult();
    }

    /**
     * @param $item
     * @param $name
     * @return ItemBlueimp
     */
    public function getFileByItemName($item, $name){
        $file = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:ItemBlueimp')
            ->findOneBy([ 'item' => $item, 'name' => $name ]);
        return $file;
    }
}