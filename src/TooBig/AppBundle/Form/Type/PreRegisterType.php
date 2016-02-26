<?php

namespace TooBig\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class PreRegisterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', 'text', [
                'attr' => [
                    'size'=>15
                ]
            ])
            ->add(
                'save',
                'button',
                [
                    'label' => 'Отправить',
                    'attr' => [
                        'class' => 'pre-register-phone-button'
                    ]
                ]
            );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'PreRegister';
    }

}