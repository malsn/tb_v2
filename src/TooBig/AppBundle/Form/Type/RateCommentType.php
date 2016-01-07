<?php

namespace TooBig\AppBundle\Form\Type;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use TooBig\AppBundle\Form\Type\RateType;


class RateCommentType extends AbstractType
{

    /**
     * @var Router
     */
    protected $route_service;
    protected $item_id;

    public function __construct(Router $route, $item_id)
    {
        $this->route_service = $route;
        $this->setItemId( $item_id );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'comment',
                'textarea',
                [
                    'label' => 'Комментарий к объявлению',
                    'attr' => [
                        'placeholder' => 'Здесь можно высказать свое мнение и поставить оценку о качестве оригинального товара в отношении бренда и модели объявления',
                        'class' => 'form-group'
                    ],
                    'constraints' => [new NotBlank()]
                ]
            )
            ->add('rate', new RateType(), ['empty_value' => 'Поставьте оценку'])
            ->add(
                'save',
                'submit',
                [
                    'label' => 'Сохранить',
                    'attr' => [
                        'class' => 'btn btn-primary',
                        'id' => 'save-rate-comment',
                        'path-controller' => $this->route_service->generate( 'app_item_comment_add', array( 'item_id' => $this->getItemId() ) )
                    ]
                ]
            );
    }

    /**
     * @return mixed
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * @param mixed $item_id
     */
    public function setItemId($item_id)
    {
        $this->item_id = $item_id;
    }



    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'RateComment';
    }
}