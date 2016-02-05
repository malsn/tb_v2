<?php

namespace TooBig\AppBundle\Form\Type;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Gregwar\CaptchaBundle\Type\CaptchaType;


class CaptchaForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('captcha', 'captcha', [
                'label' => 'Проверочный код',
            ])
            ->add(
                'save',
                'button',
                [
                    'label' => 'Показать',
                    'attr' => [
                        'class' => 'btn btn-primary item-phone-captcha'
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
        return 'Captcha';
    }
}