<?php

namespace TooBig\AppBundle\Model;

use TooBig\AppBundle\Entity\Brand;
use Symfony\Component\DependencyInjection\ContainerAware;

class BrandModel extends ContainerAware {

    /**
     * @param $brand_id
     * @return Brand
     */
    public function getBrandById($brand_id){
        $brand = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:Brand')
            ->find($brand_id);
        return $brand;
    }

    public function add($a, $b)
    {
        return $a + $b;
    }

}