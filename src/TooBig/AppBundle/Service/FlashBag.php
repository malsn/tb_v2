<?php

namespace BSS\CommunalBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;


class FlashBag
{
    const INLINE_SUCCESS = 'action_success';
    const INLINE_WARNING = 'action_warning';
    const INLINE_INFO = 'action_info';
    const INLINE_ERROR = 'action_error';
    const MESSAGE = 'messages';

    /**
     * @var Session
     */
    private $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param $message
     */
    public function addError($message)
    {
        $this->session->getFlashBag()->add(self::INLINE_ERROR, $message);
    }

    /**
     * @param $message
     */
    public function addWarning($message)
    {
        $this->session->getFlashBag()->add(self::INLINE_WARNING, $message);
    }

    /**
     * @param $message
     */
    public function addInfo($message)
    {
        $this->session->getFlashBag()->add(self::INLINE_INFO, $message);
    }

    /**
     * @param $message
     */
    public function addSuccess($message)
    {
        $this->session->getFlashBag()->add(self::INLINE_SUCCESS, $message);
    }

    /**
     * @param $message
     */
    public function addMessage($message)
    {
        $this->session->getFlashBag()->add(self::MESSAGE, $message);
    }

    /**
     *
     */
    public function clear()
    {
        $this->session->getFlashBag()->clear();
    }

}