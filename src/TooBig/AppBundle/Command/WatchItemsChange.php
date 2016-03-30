<?php

namespace TooBig\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class WatchItemsChange extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('toobig:watchitems:change')
            ->setDescription('Alert for changing items');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $users = $this->getContainer()->get('fos_user.user_manager')->findUsers();

        foreach ($users as $user) {
            $query = $this->getContainer()->get('doctrine')
                ->getRepository('TooBigAppBundle:ItemSubscribtion')
                ->createQueryBuilder('p')
                ->where('p.user = :user')
                ->orderBy('p.updatedAt', 'DESC')
                ->setParameter('user', $user->getId())
                ->getQuery();
            $watch_items = $query->getResult();
            $output->writeln('!'.count($watch_items));
            foreach ($watch_items as $key => $watch_item) {
                $item = $watch_item->getItem();
                $this->getContainer()->get('item_subscribtion_model')->updateTaskTime($watch_item);

                if ($item->getEditedAt() < $watch_item->getTaskedAt() || !$item->getEnabled()) {
                    $output->writeln('?'.$item->getEditedAt()->format('Y-m-d H:i:s').'<'.$watch_item->getTaskedAt()->format('Y-m-d H:i:s').'EN'.$item->getEnabled());
                    unset($watch_items[$key]);
                }
            }

            $output->writeln('--'.count($watch_items));

            if (count($watch_items) > 0) {
                $items_table = '<table><tr><th>№</th><th>Наименование</th></tr>';
                $i=0;
                foreach ($watch_items as $key => $watch_item) {
                    $item = $watch_item->getItem();
                    $items_table .= sprintf('<tr><td>%d</td><td><a href="http://old-stuff.spbeta.ru%s" target="_blank">%s</a></td></tr>',
                        ++$i,
                        $item->getRubric()->getFullPath().$item->getSlug(),
                        $item->getTitle());
                    }
                $items_table .= '</table>';
                // получаем 'mailer' (обязателен для инициализации Swift Mailer)
                $mailer = $this->getContainer()->get('mailer');
                $message = \Swift_Message::newInstance()
                    ->setSubject('Уведомление об изменении объявлений на площадке TooBig')
                    ->setFrom('admin@old-stuff.spbeta.ru')
                    ->setTo($user->getEmail())
                    ->setContentType('text/html')
                    ->setBody(sprintf("Уважаемый(ая), %s.<br/>В избанных Вами объявлениях произошли изменения: %s Чтобы посмотреть Избранное, перейдите по ссылке <a href='http://old-stuff.spbeta.ru%s'>Избранное</a>.",
                            $user->getFirstName(),
                            $items_table,
                            $this->getContainer()->get('router')->generate('app_list_watch_items'))
                    );
                $mailer->send($message);

                $output->writeln($items_table);

            }
            $output->writeln($user->getId());
        }

    }
}