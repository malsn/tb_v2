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

use FOS\UserBundle\Controller\ResettingController;
use Sonata\UserBundle\Model\UserInterface;

/**
 * Class ResettingFOSUser1Controller.
 *
 *
 * @author Hugo Briand <briand@ekino.com>
 */
class ResettingFOSUser1Controller extends ResettingController
{
    protected function getRedirectionUrl(UserInterface $user)
    {
        return $this->container->get('router')->generate('app_main');
    }
}
