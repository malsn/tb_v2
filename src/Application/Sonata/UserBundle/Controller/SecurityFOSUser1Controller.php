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
        $resp_login = $this->loginFormAction($data);
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
    public function loginFormAction($form_data = array())
    {
        $template = sprintf('FOSUserBundle:Security:login.html.%s', $this->container->getParameter('fos_user.template.engine'));
        return $this->container->get('templating')->renderResponse($template, $form_data);
    }
}
