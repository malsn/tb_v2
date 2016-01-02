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
use TooBig\AppBundle\Form\Type\ProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * This class is inspired from the FOS Profile Controller, except :
 *   - only twig is supported
 *   - separation of the user authentication form with the profile form.
 */
class UserController extends Controller
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
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('TooBigAppBundle:User:edit_user_profile.html.twig', array(
            'user'   => $user
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
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

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
                    $this->setFlash(
                        'notice',
                        'Ваши данные успешно обновлены!');
                } catch (\Exception $e) {
                    $this->setFlash(
                        'notice',
                        'Произошла ошибка при обновлении данных!'.$e->getMessage().$form->getErrors()->next()
                    );
                }
            } else {
                $this->setFlash(
                    'notice',
                    'Произошла ошибка при обновлении данных!'.$form->getErrors()->next()
                );
            }
        }


            return $this->render('TooBigAppBundle:User:edit_user_profile.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
        $this->get('session')->getFlashBag()->set($action, $value);
    }
}
