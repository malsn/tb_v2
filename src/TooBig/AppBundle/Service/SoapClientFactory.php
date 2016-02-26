<?php

namespace TooBig\AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;


class SoapClientFactory
{
    /**
     * @var SoapClientFake|null
     */
    private $client = null;

    /**
     * @param $url
     * @param array $options
     */
    public function __construct($url, array $options)
    {
        if (array_key_exists('connection_timeout', $options)) {
            ini_set('default_socket_timeout', $options['connection_timeout']); //возможно не работает с https
        }
        try {
            $this->client = new \SoapClient($url, $options);
        } catch (\SoapFault $e) {
            $this->client = new SoapClientFake($url);
        }
    }

    /**
     * @return SoapClientFake|null
     */
    public function getClient()
    {
        return $this->client;
    }

}