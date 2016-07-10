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
use TooBig\AppBundle\Form\Type\StatusType;


class ItemForm extends AbstractType
{

    /**
     * @var Router
     */
    protected $route_service;
    /**
     * @var Rubric
     */
    protected $rubric;

    public function __construct(Router $route, Rubric $rubric)
    {
        $this->route_service = $route;
        $this->rubric = $rubric;
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
            ->add(
                'phone',
                'text',
                [
                    'label' => 'Телефон',
                    'attr' => [
                        'placeholder' => '',
                        'class' => 'form-group'
                    ]
                ]
            )
            ->add('place', 'text', [
                'required' => true
            ])
            ->add('place_geo_lat', 'hidden', array(
                'required' => false,
            ))
            ->add('place_geo_lon', 'hidden', array(
                'required' => false,
            ))
            ->add('rubric', 'rubricchoice')
            ->add('gender', new GenderType(), ['empty_value' => 'Укажите пол'])
            ->add('status', new StatusType(), ['empty_value' => 'Укажите состояние'])
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
            ->add('size_country', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\SizeCountry',
                'empty_value' => 'Укажите производителя',
                'attr'=>[
                    'class'=>'size-country'
                ]
            ])
            ->add('size_type', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\SizeType',
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
            ->add('publication_date_end', 'date',[
                'data' => $this->getPublicationDateEnd(),
                'required' => false,
                'widget'   => 'single_text'
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

    protected function getPublicationDateEnd(){
        $pub_date = new \DateTime();
        $pub_date->add(new \DateInterval('P30D'));
        return $pub_date;
    }
}