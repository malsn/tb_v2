<?php

namespace TooBig\AppBundle\Service;


class SoapClientFake extends \SoapClient
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function __call($name, $arguments)
    {
        $message = sprintf(
            'try to call %s method for soap on %s, but soap server is down',
            $name,
            $this->url
        );

        throw new \Exception($message);
    }

}