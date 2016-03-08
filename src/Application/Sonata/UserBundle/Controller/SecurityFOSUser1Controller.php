<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController;
use Sonata\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class SecurityFOSUser1Controller.
 *
 *
 * @author Hugo Briand <briand@ekino.com>
 */
class SecurityFOSUser1Controller extends SecurityController
{
    /**
     * {@inheritdoc}
     */
    public function loginAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        if ($user instanceof UserInterface) {
            $this->container->get('session')->getFlashBag()->set('sonata_user_error', 'sonata_user_already_authenticated');
            $url = $this->container->get('router')->generate('app_main');

            return new RedirectResponse($url);
        }

        return parent::loginAction();
    }

    protected function renderLogin(array $data)
    {
        $resp_login = $this->loginFormAction();
        $resp_login = preg_replace('/[\r\n]/i','',$resp_login->getContent());
        $this->container->get('flash_bag')->addMessage(
            $resp_login
        );
        return $this->container->get('templating')->renderResponse('TooBigAppBundle::page-main-with-list.html.twig');
    }

    /**
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginFormAction()
    {
        $request = $this->container->get('request');
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $session = $request->getSession();
        /* @var $session \Symfony\Component\HttpFoundation\Session\Session */

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
        $csrfToken = $this->container->has('form.csrf_provider') ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate') : null;
        $data = array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token' => $csrfToken,
        );

        $template = sprintf('FOSUserBundle:Security:login.html.%s', $this->container->getParameter('fos_user.template.engine'));
        return $this->container->get('templating')->renderResponse($template, $data);
    }
}
