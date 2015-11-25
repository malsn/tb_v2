<?php

namespace TooBig\AppBundle\Model;

use TooBig\AppBundle\Entity\Brand;
use Symfony\Component\DependencyInjection\ContainerAware;

class ModelModel extends ContainerAware {
    /**
     * @param Brand $brand
     * @return mixed
     */
    public function getModelsByBrand(Brand $brand){
        $models = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:Model')
            ->findBy(['brand' => $brand]);
        return $models;
    }
}