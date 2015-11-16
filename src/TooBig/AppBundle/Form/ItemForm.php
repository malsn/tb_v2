<?php

namespace TooBig\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Application\Iphp\ContentBundle\Entity\Content;
use Application\Iphp\CoreBundle\Entity\Rubric;


class ItemForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                'text',
                [
                    'label' => 'Название объявления ( тип вещи )',
                    'attr' => [
                        'placeholder' => 'Название объявления ( тип вещи )'
                    ],
                    'constraints' => [new NotBlank()]
                ]
            )
            ->add('rubric', 'entity', ['class'=>'Application\Iphp\CoreBundle\Entity\Rubric'])
            ->add('color')
            ->add('brand')
            ->add('price')
            ->add('redirectToFirstFile')
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
        return 'Content';
    }
}