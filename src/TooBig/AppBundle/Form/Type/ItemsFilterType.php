<?php

namespace TooBig\AppBundle\Form\Type;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use TooBig\AppBundle\Entity\Brand;
use TooBig\AppBundle\Entity\Size;
use TooBig\AppBundle\Entity\SizeType;


class ItemsFilterType extends AbstractType
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
            ->add('brand', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Brand',
                'empty_value' => 'Бренд',
                'required'=>false,
                'label' => ' ',
                'attr'=>[
                    'class'=>'form-filter brand',
                ]
            ])
            ->add('size', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Size',
                'empty_value' => 'Размер',
                'label' => ' ',
                'attr'=>[
                    'class'=>'form-filter size'
                ]
            ])
            ->add('color', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Color',
                'empty_value' => 'Цвет',
                'label' => ' ',
                'attr'=>[
                    'class'=>'form-filter color'
                ]
            ])
            ->add('gender', new GenderType(), [
                'empty_value' => 'Пол',
                'label' => ' ',
                'attr'=>[
                    'class'=>'form-filter gender'
                ]
            ])
            ->add('price_min', 'hidden')
            ->add('price_max', 'hidden')
            ->add(
                'save',
                'button',
                [
                    'label' => 'Применить'
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
        return 'ItemsFilter';
    }
}