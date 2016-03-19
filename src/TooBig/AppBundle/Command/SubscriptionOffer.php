<?php

namespace TooBig\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Query;

class SubscriptionOffer extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('toobig:subscription:offer')
            ->setDescription('Offering new items on Users auto_subscriptions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $subscriptions = $this->getContainer()->get('doctrine')->getRepository('TooBigAppBundle:AutoSubscription')->findAll();
        foreach ( $subscriptions as $subscription){
            $user = $subscription->getCreatedBy();
            $query = $this->getContainer()->get('auto_subscription_model')->getItemsBySubscriptionQuery($subscription, 'new');
            $count = count($query->getResult());
            if ($count > 0) {
                // получаем 'mailer' (обязателен для инициализации Swift Mailer)
                $mailer = $this->getContainer()->get('mailer');
                $message = \Swift_Message::newInstance()
                    ->setSubject('Новые предложения по вашей подписке на площадке TooBig')
                    ->setFrom('admin@old-stuff.spbeta.ru')
                    ->setTo($user->getEmail())
                    ->setContentType('text/html')
                    ->setBody(sprintf("Уважаемый(ая), %s. По вашей подписке \"%s\" для вас появилось %d новых объявлений. Чтобы их посмотреть, перейдите в раздел <a href='http://old-stuff.spbeta.ru/%s'>Мои подписки</a>.".$query->getSQL(),
                        $user->getFirstName(),
                        $subscription->getTitle(),
                        $count,
                        $this->getContainer()->get('router')->generate('app_list_subscriptions'))
                    );
                $mailer->send($message);
                /**/
                $output->writeln($subscription->getId());
            }
        }

    }
}