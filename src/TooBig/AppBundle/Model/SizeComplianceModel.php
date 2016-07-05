<?php

namespace TooBig\AppBundle\Model;

use TooBig\AppBundle\Entity\Size;
use Symfony\Component\DependencyInjection\ContainerAware;
use TooBig\AppBundle\Entity\SizeCountry;
use TooBig\AppBundle\Entity\SizeType;
use TooBig\AppBundle\Entity\SizeCompliance;

class SizeComplianceModel extends ContainerAware {

    /**
     * @param Size $size_2
     * @param SizeType $size_type_2
     * @param SizeCountry $size_country_2
     * @return object|SizeCompliance
     */
    public function getComplianceBySize(Size $size_2, SizeType $size_type_2, SizeCountry $size_country_2){
        $sizeCompliance = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:SizeCompliance')
            ->findOneBy(['size_2' => $size_2, 'size_type_2' => $size_type_2, 'size_country_2' => $size_country_2]);
        return $sizeCompliance;
    }
}