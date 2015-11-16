<?php
/**
 * Created by Vitiko
 * Date: 25.01.12
 * Time: 15:29
 */

namespace Application\Iphp\ContentBundle\Module;

use Iphp\CoreBundle\Module\Module;
use Iphp\ContentBundle\Admin\Extension\RubricAdminExtension;

/**'
 *
 * Module - item - rubric index
 */
class ItemIndexModule extends Module
{

    function __construct()
    {
        $this->setName('Item - rubric index');
        $this->allowMultiple = true;
    }

    protected function registerRoutes()
    {
        $this->addRoute('index', '/', array('_controller' => 'TooBigAppBundle:Item:index'));
        //    ->addRoute('contentById','/{id}/', array('_controller' => 'IphpContentBundle:Content:contentById'));
    }

    function getAdminExtension()
    {
        return new RubricAdminExtension;
    }

}
