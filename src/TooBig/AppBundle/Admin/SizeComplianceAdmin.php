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
            ->add('size_1');
    }

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('size_1');

    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $this->addInformationBlock($formMapper);
        $this->configureFormFieldsContent($formMapper);
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

    public function setRoutingService($routing)
    {
        $this->route_service = $routing;
    }

    public function getRoutingService()
    {
        return $this->route_service;
    }

}
