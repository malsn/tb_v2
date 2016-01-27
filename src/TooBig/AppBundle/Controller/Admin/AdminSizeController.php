<?php

namespace TooBig\AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminSizeController extends Controller
{
    /**
     * @Route("/admin/size/type/", name="admin_list_size_by_sizetype")
     */
    public function listSizebySizeTypeAction( Request $request ){
        $keys = $request->request->keys();
        $size_type = $this->get('sizetype_model')->getSizeTypeById( $request->request->get($keys[1])['size_type'] );
        $form_array = $request->request->get($keys[1]);
        if (array_key_exists('size',$form_array)) {
            $item_size = $form_array['size'];
        } else {
            $item_size = null;
        }
        if (!is_null($size_type)) {
            $sizes = $this->get('size_model')->getSizeBySizeType($size_type);
            return $this->render('TooBigAppBundle:Admin\Size:size_by_sizetype.html.twig', [
                'sizes' => $sizes,
                'form_name' => $keys[1],
                'item_size' => $item_size
            ]);
        } else {
            return false;
        }
    }
}
