<?php


namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\HttpFoundation\Session;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle', 'attr'=>array('class'=>'form-control')))
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle', 'attr'=>array('class'=>'form-control')))
            ->add('phone', 'hidden', array(
                'label' => 'form.phone',
                'translation_domain' => 'FOSUserBundle',
                'attr'=>array('class'=>'form-control'),
                'data' => ( $_SESSION['register_phone'] ? : null ) ))
            ->add('place', 'text', array('label' => 'form.place', 'translation_domain' => 'FOSUserBundle', 'attr'=>array('class'=>'form-control')))
            ->add('place_geo_lat', 'hidden', array(
                'required' => false,
            ))
            ->add('place_geo_lon', 'hidden', array(
                'required' => false,
            ))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password','attr'=>array('class'=>'form-control')),
                'second_options' => array('label' => 'form.password_confirmation', 'attr'=>array('class'=>'form-control')),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
        ;
    }

    public function getName()
    {
        return 'modified_fos_user_registration';
    }
}
