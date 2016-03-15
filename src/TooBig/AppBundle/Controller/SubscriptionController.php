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
use Application\Iphp\CoreBundle\Entity\Rubric;

class SubscriptionController extends RubricAwareController
{
    protected $errors;

    /**
     * @Route("/app/subscription/add", name="app_subscribtion_add")
     */
public function addAction(Request $request)
{
        $user = $this->get('security.context')->getToken()->getUser();

        if (!is_object($user)) {

            return $this->getUserLoginForm();

        } else {

            $record = new AutoSubscription();

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

    }
}

/**
 * @Route("/app/subscription/{subscription_id}/edit", name="app_subscription_edit")
 */
public function editAction($subscription_id, Request $request)
{

    $user = $this->get('security.context')->getToken()->getUser();

    if (!is_object($user)) {

        return $this->getUserLoginForm();

    } else {

        $record = $this->get('auto_subscription_model')->getSubscriptionById($subscription_id);

        if (!is_object($record)) { throw $this->createNotFoundException('Подписки по указанному адресу не существует'); }

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

    }

}

public function deleteAction($subscription_id){

    $user = $this->get('security.context')->getToken()->getUser();

    if (!is_object($user)) {

        return $this->getUserLoginForm();

    } else {

        $record = $this->get('auto_subscription_model')->getSubscriptionById($subscription_id);

        if (!is_object($record)) { throw $this->createNotFoundException('Подписки по указанному адресу не существует'); }

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

    }

}

/**
 * @param $subscription_id
 * @return int|null
 */
public function getSubscriptionItemsCountAction($subscription_id){

    $user = $this->get('security.context')->getToken()->getUser();

    if (!is_object($user)) {

        return new Response(0);

    } else {

        $subscription = $this->get('auto_subscription_model')->getSubscriptionById($subscription_id);
        $query = $this->getSubscriptionQuery($subscription);
        return new Response(count($query->getResult()));

    }

}

/**
 * @Template("TooBigAppBundle:AutoSubscription:subscription_items.html.twig")
 */
public function getSubscriptionItemsListAction($subscription_id){

    $user = $this->get('security.context')->getToken()->getUser();

    if (!is_object($user)) {

        return $this->getUserLoginForm();

    } else {

        $subscription = $this->get('auto_subscription_model')->getSubscriptionById($subscription_id);
        $query = $this->getSubscriptionQuery($subscription);
        return array('entities' => $this->paginate($query, 20), 'subscription' => $subscription);

    }

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
protected function getSubscriptionQuery($subscription){

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

    /**
     * @return Response
     */
public function getBreadcrumbsAction( $rubric_id ){
    return $this->render(
        'TooBigAppBundle:AutoSubscription:breadcrumbs.html.twig',[
        'breadcrumbs' => array_reverse( $this->get('rubric_model')->getParentRubrics($rubric_id, []) )
        ]
    );
}

protected function getUserLoginForm(){
    $resp_login = $this->forward('ApplicationSonataUserBundle:SecurityFOSUser1:loginForm');
    $resp_login = preg_replace('/[\r\n]/i','',$resp_login->getContent());
    $_SESSION['return_url'] = $this->get('request_stack')->getMasterRequest()->server->get('REQUEST_URI');
    $this->get('flash_bag')->addMessage(
        $resp_login
    );
    return $this->forward('TooBigAppBundle:Item:siteIndex');
}

}
