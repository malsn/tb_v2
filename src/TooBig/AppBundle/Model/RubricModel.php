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

    /**
     * @param $rubric_id
     * @param $rubrics
     * @return mixed
     */
    public function getParentRubrics($rubric_id, $rubrics){
        $rubric = $this->getRubricById( $rubric_id );
        $rubrics[] = $rubric;
        if ( $rubric->getParentId() != 1 ){
            $rubrics = $this->getParentRubrics($rubric->getParentId(), $rubrics );
        }
        return $rubrics;
    }
}