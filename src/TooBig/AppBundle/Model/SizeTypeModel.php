<?php

namespace TooBig\AppBundle\Model;

use TooBig\AppBundle\Entity\SizeType;
use Symfony\Component\DependencyInjection\ContainerAware;

class SizeTypeModel extends ContainerAware {
    /**
     * @param $size_type_id
     * @return mixed
     */
    public function getSizeTypeById( $size_type_id ){
        $size_type = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:SizeType')
            ->find($size_type_id);
        return $size_type;
    }
}