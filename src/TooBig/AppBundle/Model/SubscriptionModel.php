<?php

namespace TooBig\AppBundle\Model;

use Sonata\UserBundle\Model\UserInterface;
use TooBig\AppBundle\Entity\AutoSubscription;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class SubscriptionModel
 * @package TooBig\AppBundle\Model
 */
class SubscriptionModel extends ContainerAware {

    /**
     * @param AutoSubscription $record
     */
    public function save(AutoSubscription $record){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $record->setCreatedBy($user);
        $record->setCreatedAt(new \DateTime());
        $record->setModifiedAt(new \DateTime());
        $record->setEnabled(true);

        //$pub_date = new \DateTime();
        //$pub_date->add(new \DateInterval('P30D'));
        //$record->setPublicationDateEnd($pub_date);

        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($record);
        $em->flush();
    }

    /**
     * @param AutoSubscription $record
     */
    public function delete(AutoSubscription $record){
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->remove($record);
        $em->flush();
    }

    /**
     * @param $record_id
     * @return AutoSubscription
     */
    public function getSubscriptionById($record_id){
        $record = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:AutoSubscription')
            ->find($record_id);
        return $record;
    }

    /**
     * @return array
     */
    public function getSubscriptionsByUser(){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $items = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:AutoSubscription')
            ->findBy(
                ['createdBy'=>$user],
                ['createdAt'=>'DESC']
            );
        return $items;
    }

}