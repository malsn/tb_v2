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
}

