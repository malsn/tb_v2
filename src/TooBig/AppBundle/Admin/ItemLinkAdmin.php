<?php
/**
 * Created by malsn
 */
namespace TooBig\AppBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Iphp\CoreBundle\Admin\Admin as IphpAdmin;

class ItemLinkAdmin extends IphpAdmin
{


    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('url', 'text', array ('attr' => array ('style' => 'width:200px')))
                   ->add('title')
                   ->add ('date','genemu_jquerydate', array ('required' => false, 'widget' => 'single_text'))
                   ->add('pos', 'hidden');


    }
}
