<?php

namespace TooBig\AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class AdminBlueimpController
 * @package TooBig\AppBundle\Controller
 */
class AdminBlueimpController extends Controller
{
    /**
     * @Route("/admin/item/blueimp/list", name="admin_list_blueimp_by_item")
     */
    public function listBlueimpByItemAction( Request $request ){
        $keys = $request->request->keys();
        $item = $this->get('item_model')->getItemBySlug( $request->request->get($keys[1])['slug'] );

        if ( null !== $item ){
            $existingFiles = $this->get('punk_ave.file_uploader')->getFiles(array('folder' => 'attachments/' . $item->getId()));
            return $this->render('TooBigAppBundle:Admin\Blueimp:files_by_item.html.twig', [ 'existingFiles'=>$existingFiles, 'item' => $item ]);
        } else {
            return false;
        }
    }
}
