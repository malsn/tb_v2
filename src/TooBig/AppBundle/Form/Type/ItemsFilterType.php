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
            ->setMethod('GET')
            ->add('brand', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Brand',
                'expanded'=>true,
                'multiple'=>true,
                'empty_value' => 'Бренд',
                'required'=>false,
                'label' => ' ',
                'attr'=>[
                    'class'=>'form-filter brand',
                ]
            ])
            ->add('size', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Size',
                'expanded'=>true,
                'multiple'=>true,
                'empty_value' => 'Размер',
                'required'=>false,
                'label' => ' ',
                'attr'=>[
                    'class'=>'form-filter size'
                ]
            ])
            ->add('color', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Color',
                'expanded'=>true,
                'multiple'=>true,
                'empty_value' => 'Цвет',
                'required'=>false,
                'label' => ' ',
                'attr'=>[
                    'class'=>'form-filter color'
                ]
            ])
            ->add('gender', new GenderType(), [
                'expanded'=>true,
                'multiple'=>true,
                'empty_value' => 'Пол',
                'required'=>false,
                'label' => ' ',
                'attr'=>[
                    'class'=>'form-filter gender'
                ]
            ])
            ->add('price_min', 'text', [
                'attr' => [
                    'size'=>3
                ]
            ])
            ->add('price_max', 'text', [
                'attr' => [
                    'size'=>3
                ]
            ])
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