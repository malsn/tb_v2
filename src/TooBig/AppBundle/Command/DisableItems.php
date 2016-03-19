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
            if ($today >= $publication_date_end && $item->getEnabled()){
                $item->setEnabled(false);
                $em = $this->getContainer()->get('doctrine.orm.entity_manager');
                $em->persist($item);
                $em->flush();
                /* отправить сообщение пользователю */
                $user = $item->getCreatedBy();
                // получаем 'mailer' (обязателен для инициализации Swift Mailer)
                $mailer = $this->getContainer()->get('mailer');

                $message = \Swift_Message::newInstance()
                    ->setSubject('Публикация вашего объявления на площадке TooBig')
                    ->setFrom('admin@old-stuff.spbeta.ru')
                    ->setTo($user->getEmail())
                    ->setContentType('text/html')
                    ->setBody(sprintf("Уважаемый(ая), %s. Срок публикации вашего объявления %s закончился. Для его дальнейшего показа, перейдите в раздел <a href='http://old-stuff.spbeta.ru%s'>Мои объявления</a> и активируйте его снова.",
                            $user->getFirstName(),
                            $item->getTitle(),
                            $this->getContainer()->get('router')->generate('app_list_user_items'))
                    )
                ;
                $mailer->send($message);
                /**/
                $output->writeln($item->getId());
            }
        }
    }
}