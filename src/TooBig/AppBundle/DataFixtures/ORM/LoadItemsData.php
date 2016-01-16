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

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 1');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 2');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 3');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 4');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 5');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 6');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 7');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 8');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 9');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 10');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 11');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 12');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 13');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 14');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 15');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 16');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 17');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 18');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 19');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

    $itemAdmin = $this->container->get('item_model')->makeFixtureCopy($record);
    $itemAdmin->setTitle('Happy Bee sample 20');
    $itemAdmin->setEnabled(true);
    $itemAdmin->setAbstract('Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка...');
    $itemAdmin->setContent('<p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Стильные детские ботинки Happy Bee изготовлены из искусственного нубука коричневого оттенка.</span></p><p><span style="background-color:rgb(255, 255, 255); color:rgb(34, 34, 34); font-family:helvetica neue,arial,sans-serif; font-size:13px">Детали: внутренняя отделка из ворсина, шнуровка на подъеме, закрытые щиколотки, контрастная фактурная вставка, гибкая подошва с рельефным рисунком протектора.</span></p>');
    $itemAdmin->setPrice(1000);

    $manager->persist($itemAdmin);
    $manager->flush();

    $blueimp = new ItemBlueimp();
    $blueimp->setItem($itemAdmin->getId());
    $blueimp->setName('HA014ABGTS01_1_v1.jpg');
    $blueimp->setCreatedAt(new \DateTime());
    $manager->persist($blueimp);
    $manager->flush();

    /* ------------------- */

}
}