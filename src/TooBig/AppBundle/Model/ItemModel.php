<?php

namespace TooBig\AppBundle\Model;

use Doctrine\ORM\Query;
use Sonata\UserBundle\Model\UserInterface;
use TooBig\AppBundle\Entity\Item;
use Application\Iphp\CoreBundle\Entity\Rubric;
use Symfony\Component\DependencyInjection\ContainerAware;
use TooBig\AppBundle\Entity\ItemSubscribtion;
use TooBig\AppBundle\Entity\Brand;

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
        $record->setEditedAt(new \DateTime());
        $record->setEnabled(false);



        /* установка значений даты публикации */
        $pub_date = new \DateTime();

        if ($record->getPublicationDateEnd() <= $pub_date ){
            $pub_date->add(new \DateInterval('P30D'));
            $record->setPublicationDateEnd($pub_date);
        }
        /* установка значений даты публикации */

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
        $copy->setEditedAt(new \DateTime());
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
        $copy->setEditedAt(new \DateTime());
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
     * @param $slug
     * @return Item
     */
    public function getItemBySlug($slug){
        $item = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:Item')
            ->findOneBySlug($slug);
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

    public function getItemsFilter ( Query $query ) {
        $collection = $query->getResult();

        $filters['Brand']=[];
        $filters['Size']=[];
        $filters['Color']=[];
        $filters['Gender']=[];

        if (count($collection)){
            foreach( $collection as $item ){
                $brand = $item->getBrand();
                if ( null !== $brand ){
                    if ( !in_array($brand->getId(), $filters['Brand']) ) {
                        $filters['Brand'][$brand->getId()] = $brand->getName();
                    }
                }
                $size = $item->getSizeFilter();
                if ( null !== $size ){
                    if ( !in_array($size->getId(), $filters['Size']) ) array_push($filters['Size'],$size->getId());
                }
                $color = $item->getColor();
                if ( null !== $color ){
                    if ( !in_array($color->getId(), $filters['Color']) ) {
                        $filters['Color'][$color->getId()] = $color->getCode();
                    }
                }
                $gender = $item->getGender();
                if ( null !== $gender ){
                    $choices = array(
                        'm' => 'Мальчик',
                        'f' => 'Девочка',
                        'u' => 'Унисекс',
                    );
                    if ( !in_array($gender, $filters['Gender']) && strlen($gender) ) {
                        $filters['Gender'][$gender] = $choices[$gender];
                    }
                }
            }
        }

        return $filters;

    }
}