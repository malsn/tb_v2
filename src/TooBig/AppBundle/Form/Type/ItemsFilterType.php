<?php

namespace TooBig\AppBundle\Form\Type;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use TooBig\AppBundle\Entity\Brand;
use TooBig\AppBundle\Entity\Size;
use TooBig\AppBundle\Entity\SizeType;
use Doctrine\ORM\EntityRepository;


class ItemsFilterType extends AbstractType
{

    /**
     * @var Router
     */
    protected $route_service;

    protected $filters;

    public function __construct(Router $route, $filters)
    {
        $this->route_service = $route;
        $this->filters = $filters;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET');

        if ( null !== $this->filters ){
            $builder
                ->add('brand','choice',[
                'choices' => $this->filters['Brand'],
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
                    'query_builder' => function(EntityRepository $er) {
                        $qb = $er->createQueryBuilder('u');
                        $orX = $qb->expr()->orX();
                        foreach ($this->filters['Size'] as $size) {
                            $orX->add($qb->expr()->eq('u.id', $size));
                        }
                        if ( $orX->count() ){
                            $qb->add('where',$orX);
                        }
                        //$qb->where('u.size_country = 1'); /* установить динамически от настроек сайта */
                        return $qb->orderBy('u.value', 'ASC');
                    },
                    'expanded'=>true,
                    'multiple'=>true,
                    'empty_value' => 'Размер',
                    'required'=>false,
                    'label' => ' ',
                    'attr'=>[
                        'class'=>'form-filter size'
                    ]
                ])
                ->add('color', 'choice', [
                    'choices' => $this->filters['Color'],
                    'expanded'=>true,
                    'multiple'=>true,
                    'empty_value' => 'Цвет',
                    'required'=>false,
                    'label' => ' ',
                    'attr'=>[
                        'class'=>'form-filter color'
                    ]
                ])
                ->add('gender', 'choice', [
                    'choices' => $this->filters['Gender'],
                    'expanded'=>true,
                    'multiple'=>true,
                    'empty_value' => 'Пол',
                    'required'=>false,
                    'label' => ' ',
                    'attr'=>[
                        'class'=>'form-filter gender'
                    ]
                ]);
        }

            /*->add('price_min', 'text', [
                'attr' => [
                    'size'=>3
                ]
            ])
            ->add('price_max', 'text', [
                'attr' => [
                    'size'=>3
                ]
            ])*/
        $builder
            ->add('search', 'text', [
                'attr' => [
                    'placeholder' => 'Начните поиск с указания того, что хотите...',
                    'class'=>'form-filter search',
                    'size'=>20
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

    protected function sizeQueryBuilder(){

    }
}