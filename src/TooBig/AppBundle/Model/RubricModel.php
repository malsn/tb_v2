<?php

namespace TooBig\AppBundle\Model;

use Application\Iphp\ContentBundle\Entity\Content;
use Application\Iphp\CoreBundle\Entity\Rubric;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class RubricModel
 * @package TooBig\AppBundle\Model
 */
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

    /**
     * @param $rubric
     * @param $filter_params
     * @return mixed
     */
    public function getRubricPriceRange($rubric, $filter_params, $type){
        $query = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:Item')
            ->createQuery('c', function ($qb) use ($rubric, $filter_params, $type)
            {
                $qb->add('select', $qb->expr()->$type('c.price'))
                    ->fromRubric($rubric)->whereEnabled()->whereIndex(false)->withSubrubrics(true);
                foreach ($filter_params as $key => $value) {
                    if (!empty($value)){
                        $qb_func = 'where'.$key;
                        $qb->$qb_func($value);
                    }
                }
            });

        return $query->getResult();
    }
}