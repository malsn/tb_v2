<?php

namespace TooBig\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Query;

class SubscriptionItems extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('toobig:subscription:new-offer')
            ->setDescription('Offering new items on Users auto_subscriptions')
            ->addArgument(
                'exec',
                InputArgument::REQUIRED,
                'What is the last execution datetime?'
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
        

    }
}