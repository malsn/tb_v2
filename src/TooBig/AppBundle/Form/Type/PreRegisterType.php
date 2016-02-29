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
                'required' => true,
                'attr' => [
                    'size'=>15
                ]
            ])
            ->add(
                'save',
                'button',
                [
                    'label' => 'Отправить SMS',
                    'attr' => [
                        'class' => 'pre-register-phone-button btn btn-primary'
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