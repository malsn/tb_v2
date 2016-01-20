<?php

namespace TooBig\AppBundle\Form\Type;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use TooBig\AppBundle\Form\Type\GenderType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Iphp\CoreBundle\Admin\Admin;
use Application\Iphp\ContentBundle\Entity\Content;
use Application\Iphp\CoreBundle\Entity\Rubric;
use TooBig\AppBundle\Entity\Item;


class SubscriptionType extends AbstractType
{

    /**
     * @var Router
     */
    protected $route_service;

    public function __construct(Router $route)
    {
        $this->route_service = $route;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                'text',
                [
                    'label' => 'Название подписки',
                    'attr' => [
                        'placeholder' => 'Название подписки',
                        'class' => 'form-group'
                    ],
                    'constraints' => [new NotBlank()]
                ]
            )
            ->add('rubric', 'rubricchoice',[
                'required' => false,
            ])
            ->add('gender', new GenderType(), [
                'required' => false,
                'empty_value' => 'Не указан'
            ])
            ->add('color', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Color',
                'required' => false,
                'empty_value' => 'Не указан'])
            ->add('brand', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Brand',
                'required' => false,
                'empty_value' => 'Не указан',
                'attr'=>[
                    'class'=>'brand',
                    'path-controller' => $this->route_service->generate('app_list_model_by_brand', array())
                ]
            ])
            ->add('model', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Model',
                'required' => false,
                'empty_value' => 'Не указана',
                'attr'=>[
                    'class'=>'model',
                    'disabled'=>''
                ]
            ])
            ->add('size_type', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\SizeType',
                'required' => false,
                'empty_value' => 'Не указан',
                'attr'=>[
                    'class'=>'size-type',
                    'path-controller' => $this->route_service->generate('app_list_size_by_sizetype', array())
                ]
            ])
            ->add('size', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Size',
                'required' => false,
                'empty_value' => 'Не указан',
                'attr'=>[
                    'class'=>'size',
                    'disabled'=>''
                ]
            ])
            ->add('price_min', 'text', [
                'required' => false,
                'attr' => [
                    'size'=>3
                ]
            ])
            ->add('price_max', 'text', [
                'required' => false,
                'attr' => [
                    'size'=>3
                ]
            ])
            ->add(
                'save',
                'submit',
                [
                    'label' => 'Сохранить',
                    'attr' => [
                        'class' => 'btn btn-primary'
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
        return 'Subscription';
    }

    public function setRoutingService($routing)
    {
        $this->route_service = $routing;
    }

    public function getRoutingService()
    {
        return $this->route_service;
    }
}