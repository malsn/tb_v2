<?php
/**
 * Created by malsn
 */
namespace TooBig\AppBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Iphp\CoreBundle\Admin\Admin as IphpAdmin;

class ItemFileAdmin extends IphpAdmin
{


    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('published', 'checkbox', array('required' => false))
            ->add('title', 'textarea', array('required' => false, 'attr' => array('style' => 'width:200px')))
            ->add('uploadFile', 'file', array('required' => false))
            ->add('file', 'iphp_file', array())
            ->add('pos', 'hidden');
    }
}
