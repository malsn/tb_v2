<?php

namespace Application\Iphp\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SubscribeController extends Controller
{
    /**
     * @Route("/hello/{name}", name="hello")
     * @return array
     */
    public function indexAction()
    {
        return array(
                '12345'
            );
    }
}
