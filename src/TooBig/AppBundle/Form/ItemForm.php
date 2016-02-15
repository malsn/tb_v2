<?php

namespace TooBig\AppBundle\Form;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use TooBig\AppBundle\Entity\Brand;
use TooBig\AppBundle\Entity\SizeType;
use TooBig\AppBundle\Form\Type\GenderType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Iphp\CoreBundle\Admin\Admin;
use Application\Iphp\ContentBundle\Entity\Content;
use Application\Iphp\CoreBundle\Entity\Rubric;
use TooBig\AppBundle\Entity\Item;


class ItemForm extends AbstractType
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
                    'label' => 'Название объявления ( тип вещи )',
                    'attr' => [
                        'placeholder' => 'Название объявления ( тип вещи )',
                        'class' => 'form-group'
                    ],
                    'constraints' => [new NotBlank()]
                ]
            )
            ->add('rubric', 'rubricchoice')
            ->add('gender', new GenderType(), ['empty_value' => 'Укажите пол'])
            ->add('color')
            ->add('brand', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Brand',
                'empty_value' => 'Укажите бренд',
                'attr'=>[
                    'class'=>'brand',
                    'path-controller' => $this->route_service->generate('app_list_model_by_brand', array())
                ]
            ])
            ->add('model', 'entity', [
                'class' => 'TooBig\AppBundle\Entity\Model',
                'empty_value' => 'Укажите модель',
                'required' => false,
                'attr' => [
                    'class'=>'model'
                ]
            ])
            ->add('size_type', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\SizeType',
                'empty_value' => 'Укажите размерный ряд',
                'attr'=>[
                    'class'=>'size-type',
                    'path-controller' => $this->route_service->generate('app_list_size_by_sizetype', array())
                ]
            ])
            ->add('size', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Size',
                'empty_value' => 'Укажите размер',
                'required' => false,
                'attr'=>[
                    'class'=>'size'
                ]
            ])
            ->add('content', 'textarea', [
                'attr'=>[
                    'rows'=>'5'
                ]])
            ->add('price')
            /*->add('redirectToFirstFile', 'hidden')*/
            /*->add('imagesMedia', 'sonata_type_collection', [
                'required' => true,
                'by_reference' => false
            ], [
                'edit' => 'inline',
                'sortable' => 'pos',
                'inline' => 'table',
            ])*/
            ->add(
                'save',
                'submit',
                [
                    'label' => 'Сохранить',
                    'attr' => [
                        'class' => 'btn btn-primary'
                    ]
                ]
            )
            ->add(
                'reset',
                'reset',
                [
                    'label' => 'Отменить',
                    'attr' => [
                        'class' => 'btn btn-warning'
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
        return 'Item';
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