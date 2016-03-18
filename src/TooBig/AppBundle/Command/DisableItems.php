<?php

namespace TooBig\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TooBig\AppBundle\Entity\Item;
use Sonata\UserBundle\Model\UserInterface;

class DisableItems extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('toobig:items:disable')
            ->setDescription('Disabling items on publication_date_end')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            )
            ->addOption(
                'yell',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will yell in uppercase letters'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $items = $this->getContainer()->get('doctrine')->getRepository('TooBigAppBundle:Item')->findAll();

        foreach ($items as $item){
            $today = new \DateTime();
            $publication_date_end = $item->getPublicationDateEnd();
            if ($today >= $publication_date_end){
                $item->setEnabled(false);
                $em = $this->getContainer()->get('doctrine.orm.entity_manager');
                $em->persist($item);
                $em->flush();
                /* отправить сообщение пользователю */
                $user = $item->getCreatedBy();
                /**/
                $output->writeln($item->getId());
            }
        }
    }
}