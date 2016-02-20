<?php

namespace TooBig\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class BrandRepository extends EntityRepository
{
    public function getAvailableModels(){
        $models = $this->getEntityManager()
            ->getRepository('TooBigAppBundle:Model')
            ->findBy(['brand' => $this]);
        return $models;
    }

    public function getBrandsQuery(){
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT b
            FROM TooBigAppBundle:Brand b
            ORDER BY b.name ASC'
        );
        return $query;
    }
}

