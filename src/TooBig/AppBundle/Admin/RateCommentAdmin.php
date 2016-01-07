<?php
namespace TooBig\AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use TooBig\AppBundle\Form\Type\RateType;

class RateCommentAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('comment', 'textarea', array('label' => 'Comment'))
            ->add('rate', new RateType(), array('label' => 'Rate'))
            ->add('enabled', null, array('required' => false, 'label' => 'Enable'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('comment')
            ->add('rate')
            ->add('enabled')
            ->add('user')
            ->add('item')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('comment')
            ->add('rate')
            ->add('enabled', null, array('editable' => true))
            ->add('user')
            ->add('item')
        ;
    }
}