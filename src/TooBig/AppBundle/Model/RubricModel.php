<?php

namespace TooBig\AppBundle\Model;

use Application\Iphp\ContentBundle\Entity\Content;
use Application\Iphp\CoreBundle\Entity\Rubric;
use Symfony\Component\DependencyInjection\ContainerAware;

class RubricModel extends ContainerAware {
    /**
     * @param $rubric_id
     * @return mixed
     */
    public function getRubricById($rubric_id){
        $rubric = $this->container->get('doctrine')
                    ->getRepository('Application\Iphp\CoreBundle\Entity\Rubric')
                    ->find($rubric_id);
        return $rubric;
    }
}