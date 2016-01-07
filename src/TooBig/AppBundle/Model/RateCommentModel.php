<?php

namespace TooBig\AppBundle\Model;

use Sonata\UserBundle\Model\UserInterface;
use TooBig\AppBundle\Entity\RateComment;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class RateCommentModel
 * @package TooBig\AppBundle\Model
 */
class RateCommentModel extends ContainerAware {

    /**
     * @param int $item_id
     * @param string $comment
     * @param int $rate
     * @return RateComment
     */
    public function save( RateComment $rate_comment, $item_id ){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $item = $this->container->get('item_model')->getItemById($item_id);

        /* провер€ем, что пользователь не может создавать комментарий к своему объ€влению  */
        if ($user !== $item->getCreatedBy()) {
            $rate_comment->setUser($user);
            $rate_comment->setItem($item);
            $rate_comment->setEnabled( false );
            $rate_comment->setCreatedAt(new \DateTime());
            $rate_comment->setModifiedAt(new \DateTime());
            //$rate_comment->setComment( $comment );
            //$rate_comment->setRate( $rate );
            $em = $this->container->get('doctrine.orm.entity_manager');
            $em->persist($rate_comment);
            $em->flush();
        }

        return $this->getRateCommentByItem( $item_id );
    }

    /**
     * @param int $item_id
     * @param string $comment
     * @return RateComment
     */
    public function edit( $item_id ){
        /* провер€ем, что пользователь может редактировать комментарий только к созданному ранее объ€влению  */
        $rate_comment = $this->getRateCommentByItem( $item_id );
        if ( !is_null( $rate_comment ) ) {
            $rate_comment->setModifiedAt(new \DateTime());
            //$rate_comment->setComment( $comment );
            $em = $this->container->get('doctrine.orm.entity_manager');
            $em->persist($rate_comment);
            $em->flush();
        }

        return $rate_comment;
    }

    /**
     * @param $item_id
     * @return RateComment
     */
    public function getRateCommentByItem( $item_id ){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $rate_comment = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:RateComment')
            ->findOneBy([ 'item' => $item_id, 'user' => $user->getId()]);
        return $rate_comment;
    }

    /**
     * @param $item_id
     * @return RateComment
     */
    public function getAvgRateByItem( $item_id ){
        $em = $this->container->get('doctrine.orm.entity_manager');
        $query = $em->createQuery(
        'SELECT AVG(rc.rate) AS avg_rate
         FROM TooBigAppBundle:RateComment rc
         WHERE rc.item = :item_id AND rc.enabled = :enabled')
            ->setParameter('item_id', $item_id)
            ->setParameter('enabled', true );

        return $query->getResult();
    }
}