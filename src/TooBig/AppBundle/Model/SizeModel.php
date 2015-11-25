<?php

namespace TooBig\AppBundle\Model;

use TooBig\AppBundle\Entity\Size;
use Symfony\Component\DependencyInjection\ContainerAware;
use TooBig\AppBundle\Entity\SizeType;

class SizeModel extends ContainerAware {

    public function getSizeBySizeType(SizeType $size_type){
        $sizes = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:Size')
            ->findBy(['size_type' => $size_type]);
        return $sizes;
    }
}