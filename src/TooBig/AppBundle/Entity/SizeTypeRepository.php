<?php

namespace TooBig\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SizeTypeRepository extends EntityRepository
{
    public function getAvailableSizes(){
        $sizes = $this->getEntityManager()
            ->getRepository('TooBigAppBundle:Size')
            ->findBy(['size_type' => $this]);
        return $sizes;
    }
}

