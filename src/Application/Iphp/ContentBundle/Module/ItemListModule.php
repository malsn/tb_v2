<?php
/**
 * Created by Vitiko
 * Date: 25.01.12
 * Time: 15:29
 */

namespace Application\Iphp\ContentBundle\Module;

use Iphp\CoreBundle\Module\Module;


/**
 * Module - item list
 */
class ItemListModule extends Module
{

    function __construct()
    {
        $this->setName('Item list');
        $this->allowMultiple = true;
    }

    protected function registerRoutes()
    {
        $this->addRoute('index', '/', array('_controller' => 'TooBigAppBundle:Item:list'))
             ->addRoute('contentBySlug', '/{slug}/', array('_controller' => 'TooBigAppBundle:Item:contentBySlug'));
    }

}
