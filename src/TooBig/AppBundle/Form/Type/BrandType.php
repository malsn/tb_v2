<?php
namespace TooBig\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class BrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand', 'entity', array(
                'class'=>'TooBigAppBundle:Brand'
            ))
        ;

        $builder->addEventListener(
        FormEvents::PRE_SET_DATA,
        function (FormEvent $event) {
            $form = $event->getForm();

            $data = $event->getData();
            $brand = null === $data ? null : $data->getBrand();
            $models = null === $brand ? array() : $brand->getAvailableModels();

            $form->add('model', 'entity', array(
                'class'       => 'TooBigAppBundle:Model',
                'empty_value' => 'Укажите модель',
                'choices'     => $models,
            ));
        }
        );

    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'brand';
    }
}