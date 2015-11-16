<?php

namespace TooBig\AppBundle\Model;

use Application\Iphp\ContentBundle\Entity\Content;
use Application\Iphp\CoreBundle\Entity\Rubric;
use Symfony\Component\DependencyInjection\ContainerAware;

class ItemModel extends ContainerAware {
    /**
     * @param Content $record
     */
    public function save(Content $record){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $record->setCreatedBy($user);
        $record->setUpdatedBy($user);
        $record->setEnabled(false);
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($record);
        $em->flush();
    }
}