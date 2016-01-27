<?php

namespace TooBig\AppBundle\Controller\Admin;

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
class AdminModelController extends ContentController
{
    /**
     * @Route("/admin/brand/model/", name="admin_list_model_by_brand")
     */
    public function listModelbyBrandAction( Request $request ){
        $keys = $request->request->keys();
        $brand = $this->get('brand_model')->getBrandById( $request->request->get($keys[1])['brand'] );
        $form_array = $request->request->get($keys[1]);
        if (array_key_exists('model',$form_array)) {
            $item_model = $form_array['model'];
        } else {
            $item_model = null;
        }
        if (!is_null($brand)){
            $models = $this->get('model_model')->getModelsByBrand( $brand );
            return $this->render('TooBigAppBundle:Admin\Model:models_by_brand.html.twig', [
                'models'=>$models,
                'form_name'=>$keys[1],
                'item_model' => $item_model
            ]);
        } else {
            return false;
        }
    }
}
