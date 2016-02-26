<?php

namespace TooBig\AppBundle\Model;

use TooBig\AppBundle\Entity\PreRegister;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class PreRegisterModel
 * @package TooBig\AppBundle\Model
 */
class PreRegisterModel extends ContainerAware {

    /**
     * @param PreRegister $record
     */
    public function save(PreRegister $record){
        $record->setStatus(false);
        $record->setCreatedAt(new \DateTime());
        $record->setUpdatedAt(new \DateTime());
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($record);
        $em->flush();
    }

    /**
     * @param PreRegister $record
     */
    public function update(PreRegister $record){
        $record->setUpdatedAt(new \DateTime());
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($record);
        $em->flush();
    }

    /**
     * @param $phone
     * @return PreRegister
     */
    public function getPreRegisterByPhone($phone){
        $record = $this->container->get('doctrine')
            ->getRepository('TooBigAppBundle:PreRegister')
            ->findOneBy(['phone' => $phone]);
        return $record;
    }

    public function generatePreRegisterCode(){
        return mt_rand(12345,98765);
    }

    public function sendCodeWithSoap(PreRegister $record){
        try {
            $soap = $this->container->get('soap.smsc')->getClient();

            $soap_response = $soap->send_sms(
                [
                    'login' => $this->container->getParameter('soap.smsc.user'),
                    'psw' => $this->container->getParameter('soap.smsc.password'),
                    'phones' => $record->getPhone(),
                    'mes' => $record->getCode(),
                    'id' => '',
                    'sender' => '',
                    'time' => 0
                ]
            );

            if ( null !== $soap_response ) {

                if ($soap_response['id'] != ''){
                    $record->setSms($soap_response['id']);
                    $record->setStatus(true);
                    $record->setCost($soap_response['cost']);
                    $this->update($record);
                    return ['success' => 'Проверочный код отправлен вам на указанный номер!'];

                } else {
                    return ['error' => 'Получена ошибка отправки SMS - '.$soap_response['error'].', попробуйте позже'];
                }

            } else {
                return ['error' => 'Произошла ошибка отправки SMS, попробуйте позже'];
            }
        } catch (\Exception $e) {
            return ['error' => 'Произошла ошибка подключения к сервису SMS, попробуйте позже'];
        }
    }

}