<?php

namespace TooBig\AppBundle\Model;

use TooBig\AppBundle\Entity\Item;
use Application\Iphp\CoreBundle\Entity\Rubric;
use Symfony\Component\DependencyInjection\ContainerAware;

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
     * @param $item_id
     * @return mixed
     */
    public function getItemById($item_id){
        $item = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:Item')
            ->find($item_id);
        return $item;
    }
}