<?php

namespace TooBig\AppBundle\Model;

use TooBig\AppBundle\Entity\SizeCountry;
use Symfony\Component\DependencyInjection\ContainerAware;

class SizeCountryModel extends ContainerAware {
    /**
     * @param $size_country_id
     * @return mixed
     */
    public function getSizeCountryById( $size_country_id ){
        $size_country = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:SizeCountry')
            ->find($size_country_id);
        return $size_country;
    }
}