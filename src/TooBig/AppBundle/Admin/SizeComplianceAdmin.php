<?php


namespace TooBig\AppBundle\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Iphp\CoreBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Sonata\AdminBundle\Admin\AdminInterface;
use TooBig\AppBundle\Form\Type\SizeTypeType;

class SizeComplianceAdmin extends Admin
{
    /**
     * @var Route
     */
    protected $route_service;


    /*    function configure()
    {
        $this->configurationPool->getAdminByAdminCode('iphp.core.admin.rubric')
                ->addExtension( new RubricAdminExtension);
    }*/

    public function __construct($code, $class, $baseControllerName)
    {
        parent::__construct($code, $class, $baseControllerName);

        if (!$this->hasRequest()) {
            $this->datagridValues = array(
                '_per_page' => 30,
                '_page' => 1,
                '_sort_order' => 'DESC', // sort direction
                '_sort_by' => 'updatedAt' // field name
            );
        }
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('enabled', null, array('editable' => true))
            ->add('rubric')
            /*       ->add('image', 'text', array(
                'template' => 'IphpCoreBundle::image_preview.html.twig'
            ))*/
            ->add('size_country')
            ->add('createdBy')
            ->add('updatedAt');
    }

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('enabled')
            ->add('title')
            ->add('abstract')
            ->add('content');

    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $this->configureFormFieldsBaseParams($formMapper);
        $this->configureFormFieldsAttributes($formMapper);


        $this->addInformationBlock($formMapper);

        $this->configureFormFieldsContent($formMapper);


        /*       $formMapper

                   ->with('Meta', array('class' => 'col-md-6'))->end();*/

    }


    protected function configureFormFieldsBaseParams(FormMapper $formMapper)
    {
        $formMapper->with('Base params', array('class' => 'col-md-8'));
        $formMapper->add('title')

            ->add('slug', 'slug_text', array(
                'blank_title' => 'is rubric index (no slug)',
                'source_field' => 'title',
                'usesource_title' => 'use content title',
                'required' => false
            ))
            ->add('rubric', 'rubricchoice')


            /*->add('redirectUrl')
            ->add('redirectToFirstFile', null, ['required' => false])*/
            ->add('abstract')
            ->end();
    }


    function configureFormFieldsAttributes(FormMapper $formMapper)
    {
        $formMapper->with('Attributes', array('class' => 'col-md-4'))
            ->add('enabled', null, array('required' => false, 'label' => 'Show content on website'))
            ->add('publication_date_end', 'sonata_type_datetime_picker', [
                'required' => false,
                'format' => 'dd.MM.yyyy H:mm',
                'datepicker_use_button' => false
            ])
            ->end();

        if ($this->subject && $this->subject->getRubric()) {
            $url = $this->configurationPool->getContainer()->get('iphp.core.entity.router')
                ->entitySiteUrl($this->subject);
            $formMapper->setHelps(['enabled' => '<a target="_blank" href="' . $url . '">' . $url . '</a>']);
        }
    }

    protected function configureFormFieldsContent(FormMapper $formMapper)
    {
        $formMapper->with('SizeCompliance', array('class' => 'col-md-12'))
            ->add('size_country_1', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\SizeCountry',
                'empty_value' => 'Укажите страну-производителя',
                'attr'=>[
                    'class'=>'size-country',
                ]
            ])
            ->add('size_type_1', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\SizeType',
                'empty_value' => 'Укажите размерный ряд',
                'attr'=>[
                    'class'=>'size-type',
                    'path-controller' => $this->route_service->generate('admin_list_size_by_sizetype', array())
                ]
            ])
            ->add('size_1', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Size',
                'empty_value' => 'Укажите размер',
                'attr'=>[
                    'class'=>'size'
                ]
            ])

            ->add('size_country_2', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\SizeCountry',
                'empty_value' => 'Укажите страну-производителя',
                'attr'=>[
                    'class'=>'size-country',
                ]
            ])
            ->add('size_type_2', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\SizeType',
                'empty_value' => 'Укажите размерный ряд',
                'attr'=>[
                    'class'=>'size-type',
                    'path-controller' => $this->route_service->generate('admin_list_size_by_sizetype', array())
                ]
            ])
            ->add('size_2', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Size',
                'empty_value' => 'Укажите размер',
                'attr'=>[
                    'class'=>'size'
                ]
            ])
            ->end();
    }


    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('rubric', null, array(), null, array(
                'property' => 'TitleLevelIndented',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.left', 'ASC');
                }
            ))
            ->add('title')
            ->add('enabled')
            ->add('id')//     ->add('date')
            ->add('createdBy')
        ;
    }

    public function prePersist($content)
    {
        if (!$content->getSlug()) $content->setSlug('');

        parent::prePersist($content);
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
