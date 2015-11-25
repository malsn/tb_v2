<?php


namespace TooBig\AppBundle\Admin;

use TooBig\AppBundle\Form\Type\BrandType;
use TooBig\AppBundle\Form\Type\GenderType;
use FOS\UserBundle\Model\UserManagerInterface;
use TooBig\AppBundle\Entity\Item;
use Iphp\CoreBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;


use Sonata\AdminBundle\Admin\AdminInterface;
use TooBig\AppBundle\Form\Type\SizeTypeType;

class ItemAdmin extends Admin
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;


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


            ->add('redirectUrl')
            ->add('redirectToFirstFile', null, ['required' => false])
            ->add('abstract')
            ->end();
    }


    function configureFormFieldsAttributes(FormMapper $formMapper)
    {
        $formMapper->with('Attributes', array('class' => 'col-md-4'))
            ->add('enabled', null, array('required' => false, 'label' => 'Show content on website'))
            ->add('date', 'sonata_type_datetime_picker', [
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
        $formMapper->with('Item', array('class' => 'col-md-12'))

            ->add('content', 'ckeditor', array('label' => 'Описание объявления'))


            ->add('imagesMedia', 'sonata_type_collection', [
                'required' => true,
                'by_reference' => false
            ], [
                'edit' => 'inline',
                'sortable' => 'pos',
                'inline' => 'table',
            ])


            /*->add('filesMedia', 'sonata_type_collection',
                array(
                    'required' => false,
                    'by_reference' => false
                ),
                array(
                    'edit' => 'inline',
                    'sortable' => 'pos',
                    'inline' => 'table',
                )
            )


            ->add('imageUpload', 'file', ['required' => false])
            ->add('image', 'iphp_file',['upload' => false])
                         ->add('images', 'sonata_type_collection',
                                     array('by_reference' => false),
                                     array(
                                         'edit' => 'inline',
                                         'sortable' => 'pos',
                                         'inline' => 'table',
                                     ))*/

            /*->add('files', 'sonata_type_collection',
                array('by_reference' => false),
                array(
                    'edit' => 'inline',
                    'sortable' => 'pos',
                    'inline' => 'table',
                ))

            ->add('links', 'sonata_type_collection',
                array('by_reference' => false),
                array(
                    'edit' => 'inline',
                    'sortable' => 'pos',
                    'inline' => 'table',
                ))*/
            ->add('brand', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Brand',
                'empty_value' => 'Укажите бренд',
                'attr'=>[
                    'class'=>'brand',
                    'path-controller'=>'/app_dev.php/brand/model/'
                    ]
                ])
            ->add('model', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Model',
                'empty_value' => 'Укажите модель',
                'attr'=>[
                    'class'=>'model',
                    'disabled'=>''
                ]
            ])
            ->add('size_type', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\SizeType',
                'empty_value' => 'Укажите размерный ряд',
                'attr'=>[
                    'class'=>'size-type',
                    'path-controller'=>'/app_dev.php/size/type/'
                ]
            ])
            ->add('size', 'entity', [
                'class'=>'TooBig\AppBundle\Entity\Size',
                'empty_value' => 'Укажите размер',
                'attr'=>[
                    'class'=>'size',
                    'disabled'=>''
                ]
            ])
            /*->add('brand', new BrandType(), [ 'compound' => true ])
            ->add('size_type', new SizeTypeType(), [ 'compound' => true ])*/
            ->add('color')
            ->add('gender', new GenderType(), ['empty_value' => 'Укажите пол'])
            ->add('price', 'text', ['required' => true])
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
            ->add('id')//     ->add('date')// ->add('author')
        ;
    }

    public function prePersist($content)
    {
        if (!$content->getSlug()) $content->setSlug('');

        parent::prePersist($content);
    }


    public function postUpdate($content)
    {
        parent::postUpdate($content);
        $this->populateFields($content);
    }

    public function postPersist($content)
    {
        parent::postPersist($content);
        $this->populateFields($content);
    }

    protected function populateFields(Item $content)
    {
        if ($content->getRedirectToFirstFile()) {
            foreach ($content->getFiles() as $contentFile) {
                if (!$contentFile->getPublished()) continue;
                $file = $contentFile->getFile();
                if ($file) {
                    $content->setRedirectUrl($file['path']);
                    $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager')->persist($content);
                    $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager')->flush();
                }

                break;
            }
        }
    }


    public function setUserManager($userManager)
    {
        $this->userManager = $userManager;
    }

    public function getUserManager()
    {
        return $this->userManager;
    }

}
