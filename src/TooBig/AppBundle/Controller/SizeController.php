<?php

namespace TooBig\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SizeController extends Controller
{
    /**
     * @Route("/size/type/", name="list_size_by_sizetype")
     */
    public function listSizebySizeTypeAction( Request $request ){
        $keys = $request->request->keys();
        $size_type = $this->get('sizetype_model')->getSizeTypeById( $request->request->get($keys[1])['size_type'] );
        if (!is_null($size_type)) {
            $sizes = $this->get('size_model')->getSizeBySizeType($size_type);
            return $this->render('TooBigAppBundle:Size:size_by_sizetype.html.twig', ['sizes' => $sizes, 'form_name' => $keys[1]]);
        } else {
            return false;
        }
    }
}
