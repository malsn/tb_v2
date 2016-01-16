<?php
namespace TooBig\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TooBig\AppBundle\Entity\Item;
use TooBig\AppBundle\Entity\Color;
use TooBig\AppBundle\Entity\ItemBlueimp;

class LoadItemsData implements FixtureInterface, ContainerAwareInterface
{
/**
 * @var ContainerInterface
 */
private $container;

/**
 * {@inheritDoc}
 */
public function setContainer(ContainerInterface $container = null)
{
    $this->container = $container;
}

public function load(ObjectManager $manager)
{
    $record = $this->container->get('item_model')->getItemById(1);

    for ($i=1; $i<21; $i++) {

        $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
        $itemAdmin->setTitle('Happy Bee sample '.$i);
        $itemAdmin->setEnabled(true);
        $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
        $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
        $itemAdmin->setPrice(1000);

        $manager->persist($itemAdmin);
        $manager->flush();

        $blueimp = new ItemBlueimp();
        $blueimp->setItem($itemAdmin);
        $blueimp->setName('HA014ABGTS01_1_v1.jpg');
        $blueimp->setCreatedAt(new \DateTime());
        $manager->persist($blueimp);
        $manager->flush();

        /* ------------------- */

    }
}
}