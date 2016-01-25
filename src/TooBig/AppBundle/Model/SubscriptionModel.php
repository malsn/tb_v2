<?php

namespace TooBig\AppBundle\Model;

use Sonata\UserBundle\Model\UserInterface;
use TooBig\AppBundle\Entity\AutoSubscription;
use Symfony\Component\DependencyInjection\ContainerAware;
use Doctrine\ORM\Query;

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

    /**
     * @param AutoSubscription $subscription
     * @return Query
     */
    public function getItemsBySubscriptionQuery(AutoSubscription $subscription){

        $rubric = $subscription->getRubric();
        $filter_params = [];
        $price_params = [];
        $filter_params['Brand'] = $subscription->getBrand();
        $filter_params['Model'] = $subscription->getModel();
        $filter_params['SizeType'] = $subscription->getSizeType();
        $filter_params['Size'] = $subscription->getSize();
        $filter_params['Color'] = $subscription->getColor();
        $filter_params['Gender'] = $subscription->getGender();
        $price_params['Min'] = $subscription->getPriceMin();
        $price_params['Max'] = $subscription->getPriceMax();

        $query = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:Item')
            ->createQuery('c', function ($qb) use ($rubric, $filter_params, $price_params, $subscription)
            {
                $qb->andWhere($qb->expr()->neq('c.createdBy', $subscription->getCreatedBy()->getId()));
                $qb->whereEnabled()->whereIndex(false);

                if (null !== $rubric) {
                    $qb->fromRubric($rubric)->withSubrubrics(true);
                }
                foreach ($filter_params as $key => $value) {
                    if ( null !== $value ){
                        $qb_func = 'where'.$key;
                        $qb->$qb_func($value);
                    }
                }
                if (null !== $price_params['Min'] && null !== $price_params['Max']){
                    $qb->andWhere($qb->expr()->between('c.price', $price_params['Min'], $price_params['Max']));
                }
                $qb->addOrderBy ('c.date','DESC')->addOrderBy ('c.updatedAt','DESC');
            });

        return $query;

    }

}