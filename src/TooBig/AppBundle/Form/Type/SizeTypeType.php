<?php
namespace TooBig\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use TooBig\AppBundle\Entity\SizeType;

class SizeTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('size_type', 'entity', array(
                'class'=>'TooBigAppBundle:SizeType',
                'empty_value' => 'Укажите размерный ряд',
            ))
        ;

        $formModifier = function (FormInterface $form, SizeType $size_type = null) {
            $sizes = null === $size_type ? array() : $size_type->getSizes();
            $form->add('size', 'entity', array(
                'class'       => 'TooBigAppBundle:Size',
                'empty_value' => 'Укажите размер',
                'choices'     => $sizes,
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $formModifier($event->getForm(), null === $data ? new SizeType() : $data->getSizeType());
            }
        );

        $builder->get('size_type')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $size_type = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $size_type);
            }
        );
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'size_type';
    }
}