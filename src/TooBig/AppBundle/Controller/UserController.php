<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TooBig\AppBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use TooBig\AppBundle\Form\Type\ProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Iphp\CoreBundle\Controller\RubricAwareController;
use TooBig\AppBundle\Entity\AutoSubscription;

/**
 * This class is inspired from the FOS Profile Controller, except :
 *   - only twig is supported
 *   - separation of the user authentication form with the profile form.
 */
class UserController extends RubricAwareController
{
    /**
     * @return Response
     *
     * @throws AccessDeniedException
     */
    public function showAction()
    {

        $user = $this->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {

            return $this->getUserLoginForm();

        } else {

            return $this->render('TooBigAppBundle:User:edit_user_profile.html.twig', array(
                'user'   => $user, 'breadcrumbs' => []
            ));

        }

    }

    /**
     * @Template("TooBigAppBundle:User:watch_items.html.twig")
     */
    public function watchlistAction (){

        $user = $this->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {

            return $this->getUserLoginForm();

        } else {

            $repository = $this->getDoctrine()->getRepository('TooBigAppBundle:ItemSubscribtion');
            $query = $repository->createQueryBuilder('p')
                ->where('p.user = :user')
                ->orderBy('p.updatedAt', 'DESC')
                ->setParameter('user', $user->getId());

            return array('entities' => $this->paginate($query, 20));

        }

    }

    /**
     * @Template("TooBigAppBundle:User:user_items.html.twig")
     */
    public function listUserItemsAction (){

        $user = $this->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {

            return $this->getUserLoginForm();

        } else {

            $query = $this->getDoctrine()->getRepository('TooBigAppBundle:Item')->createQuery('c', function ($qb) use ($user)
            {
                $qb->whereCreatedBy($user);
            });
            return array('entities' => $this->paginate($query, 20));

        }
    }

    /**
     * @Template("TooBigAppBundle:User:user_subscriptions.html.twig")
     */
    public function listSubscriptionsAction (){

        $user = $this->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {

            return $this->getUserLoginForm();

        } else {

            $query = $this->getDoctrine()
                ->getRepository('TooBigAppBundle:AutoSubscription')
                ->createQueryBuilder('s')
                ->where('s.createdBy = :user')
                ->setParameter('user',$user->getId())
                ->orderBy('s.createdAt','DESC')
                ->getQuery();

            return array('entities' => $this->paginate($query, 20));

        }

    }

    public function showAccountAction(){

        return $this->render('TooBigAppBundle:User:show_user_account.html.twig', array(
        ));
    }

    /**
     * @return Response|RedirectResponse
     *
     * @throws AccessDeniedException
     */
    public function editProfileAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {

            return $this->getUserLoginForm();

        } else {

            $form = $this->createForm(
                new ProfileType('FOS\UserBundle\Entity\User'),
                $user
            );

            if ($request->isMethod('POST')) {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    try {
                        $this->get('fos_user.user_manager')->updateUser($user);
                        $this->get('fos_user.user_manager')->reloadUser($user);
                        $this->get('flash_bag')->addMessage('Ваши данные успешно обновлены!');
                    } catch (\Exception $e) {
                        $this->get('flash_bag')->addMessage('Произошла ошибка при обновлении данных!'.$e->getMessage().$form->getErrors()->next());
                    }
                } else {
                    $this->get('flash_bag')->addMessage('Произошла ошибка при обновлении данных!'.$form->getErrors()->next());
                }
            }


            return $this->render('TooBigAppBundle:User:edit_user_profile.html.twig', array(
                'form' => $form->createView()
            ));

        }

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
