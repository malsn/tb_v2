<?php

namespace TooBig\AppBundle\Controller;

use TooBig\AppBundle\Entity\AutoSubscription;
use TooBig\AppBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use TooBig\AppBundle\Form\ItemForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TooBig\AppBundle\Form\Type\ItemsFilterType;
use TooBig\AppBundle\Form\Type\SubscriptionType;
use Iphp\CoreBundle\Controller\RubricAwareController;

class SubscriptionController extends RubricAwareController
{
    protected $errors;

    /**
     * @Route("/app/subscription/add", name="app_subscribtion_add")
     */
    public function addAction(Request $request)
    {
        $record = new AutoSubscription();
        $user = $this->get('security.context')->getToken()->getUser();

        if (is_object($user)) {

            $form = $this->createForm(
                new SubscriptionType($this->get('router')),
                $record
            );

            if ($request->isMethod('POST')) {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    try {
                        $this->get('auto_subscription_model')->save($record);
                        $this->get('flash_bag')->addMessage('Ваша подписка успешно создана. Спасибо!');

                        return $this->redirect($this->generateUrl('app_subscription_edit',['subscription_id' => $record->getId()]));
                    } catch (\Exception $e) {
                        $this->get('flash_bag')->addMessage('Your changes were not saved!'.$e->getMessage());
                    }
                } else {
                    $this->get('flash_bag')->addMessage('Your changes were not saved!'.$form->getErrors()->next());
                }
            }

            return $this->render('TooBigAppBundle:AutoSubscription:add_subscription.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }


    }

/**
 * @Route("/app/subscription/{subscription_id}/edit", name="app_subscription_edit")
 */
public function editAction($subscription_id, Request $request)
{
    $record = $this->get('auto_subscription_model')->getSubscriptionById($subscription_id);

    if (!is_object($record)) { throw $this->createNotFoundException('Подписки по указанному адресу не существует'); }

    $user = $this->get('security.context')->getToken()->getUser();

    if (is_object($user)) {

        if ( $user === $record->getCreatedBy()){

            $form = $this->createForm(
                new SubscriptionType($this->get('router')),
                $record
            );

            if ($request->isMethod('POST')) {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    try {
                        $this->get('auto_subscription_model')->save($record);
                        $this->get('flash_bag')->addMessage('Ваша подписка успешно изменена. Спасибо!');

                    } catch (\Exception $e) {
                        $this->get('flash_bag')->addMessage('Изменения не сохранены! '.$e->getMessage());
                    }
                } else {
                    $this->get('flash_bag')->addMessage('Изменения не сохранены! Ошибка валидации формы. '.$form->getErrors()->next());
                }
            }

            return $this->render('TooBigAppBundle:AutoSubscription:edit_subscription.html.twig', [
                'form' => $form->createView(),
                'posting' => $record
            ]);

    } else {
            $this->get('flash_bag')->addMessage('<div>Данная подписка создана не вами. Если у вас есть вопросы, задайте их администратору сайта. Спасибо за понимание.</div>');
            $form = $this->createForm(
                new SubscriptionType($this->get('router')),
                new AutoSubscription()
            );
            return $this->render( 'TooBigAppBundle:AutoSubscription:edit_subscription.html.twig', ['form' => $form->createView()] );
        }

    } else {
        return $this->redirect($this->generateUrl('fos_user_security_login'));
    }
}

public function deleteAction($subscription_id){

    $record = $this->get('auto_subscription_model')->getSubscriptionById($subscription_id);
    $user = $this->get('security.context')->getToken()->getUser();

    if (!is_object($record)) { throw $this->createNotFoundException('Подписки по указанному адресу не существует'); }

    if (is_object($user)) {

        if ( $user === $record->getCreatedBy()){

                try {
                    $this->get('auto_subscription_model')->delete($record);
                    $this->get('flash_bag')->addMessage('Ваша подписка успешно удалена!');

                } catch (\Exception $e) {
                    $this->get('flash_bag')->addMessage('Ваша подписка не удалилась!'.$e->getMessage());
                }

        } else {
            $this->get('flash_bag')->addMessage('<div>Данная подписка создана не вами. Если у вас есть вопросы, задайте их администратору сайта. Спасибо за понимание.</div>');
        }

        return $this->forward('TooBigAppBundle:User:listSubscriptions',[]);

    } else {
        return $this->redirect($this->generateUrl('fos_user_security_login'));
    }
}

    /**
     * @param $subscription_id
     * @return int|null
     */
    public function getSubscriptionItemsCountAction($subscription_id){
        $subscription = $this->get('auto_subscription_model')->getSubscriptionById($subscription_id);
        $query = $this->getSubscriptionQuery($subscription);
        return new Response(count($query->getResult()));
    }

    /**
     * @Template("TooBigAppBundle:AutoSubscription:subscription_items.html.twig")
     */
    public function getSubscriptionItemsListAction($subscription_id){
        $subscription = $this->get('auto_subscription_model')->getSubscriptionById($subscription_id);
        $query = $this->getSubscriptionQuery($subscription);
        return array('entities' => $this->paginate($query, 20), 'subscription' => $subscription);
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $error
     */
    public function setErrors($error)
    {
        $this->errors[] = $error;
    }

    /**
     * @param $subscription
     * @return null
     */
    public function getSubscriptionQuery($subscription){

        $user = $this->get('security.context')->getToken()->getUser();

        if (!is_object($subscription)) { throw $this->createNotFoundException('Подписки по указанному адресу не существует'); }

        if (is_object($user)) {

            if ( $user === $subscription->getCreatedBy()){

                try {
                    $query = $this->get('auto_subscription_model')->getItemsBySubscriptionQuery($subscription);
                    return $query;

                } catch (\Exception $e) {
                    $this->setErrors($e->getMessage());
                    return null;
                }

            } else {
                return null;
            }
        }
    }
}
