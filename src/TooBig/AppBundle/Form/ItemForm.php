<?php

namespace TooBig\AppBundle\Form;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
    protected $doctrine;
    protected $brand;
    protected $size_type;

    /**
     * @param RegistryInterface $doctrine
     * @param Router $route
     * @param Brand $brand
     * @param SizeType $sizeType
     */
    public function __construct(RegistryInterface $doctrine, Router $route, Brand $brand, SizeType $sizeType)
    {
        $this->route_service = $route;
        $this->doctrine = $doctrine;
        $this->brand = $brand;
        $this->size_type = $sizeType;
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
                    'class'=>'',
                    'path-controller' => $this->route_service->generate('app_list_model_by_brand', array())
                ]
            ])
            ->add('model', 'choice', [
                'choices' => $this->getModelChoices(),
                'empty_value' => 'Укажите модель',
                'attr' => [
                    'class'=>'model'
                ]
            ])
            ->add('size_type', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\SizeType',
                'empty_value' => 'Укажите размерный ряд',
                'attr'=>[
                    'class'=>'',
                    'path-controller' => $this->route_service->generate('app_list_size_by_sizetype', array())
                ]
            ])
            ->add('size', 'choice', [
                'choices' => $this->getSizeChoices(),
                'empty_value' => 'Укажите размер',
                'attr'=>[
                    'class'=>'size'
                ]
            ])
            ->add('content', 'ckeditor')
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

    /**
     * @return array
     */
    private function getModelChoices()
    {
        $models = $this->doctrine->getRepository('TooBigAppBundle:Model')
            ->findBy(['brand' => $this->brand]);

        return $models;
    }

    /**
     * @return mixed
     */
    private function getSizeChoices()
    {
        $sizes = $this->doctrine->getRepository('TooBigAppBundle:Size')
            ->findBy(['size_type' => $this->size_type]);

        return $sizes;
    }

}