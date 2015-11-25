<?php

namespace TooBig\AppBundle\Controller;

use Iphp\ContentBundle\Controller\ContentController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class ModelController
 * @package TooBig\AppBundle\Controller
 */
class ModelController extends ContentController
{
    /**
     * @Route("/brand/model/", name="list_model_by_brand")
     */
    public function listModelbyBrandAction( Request $request ){
        $keys = $request->request->keys();
        $brand = $this->get('brand_model')->getBrandById( $request->request->get($keys[1])['brand'] );
        if (!is_null($brand)){
            $models = $this->get('model_model')->getModelsByBrand( $brand );
            return $this->render('TooBigAppBundle:Model:models_by_brand.html.twig', ['models'=>$models, 'form_name'=>$keys[1]]);
        } else {
            return false;
        }
    }
}
