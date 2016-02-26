<?php

namespace TooBig\AppBundle\Controller;

use TooBig\AppBundle\Entity\PreRegister;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TooBig\AppBundle\Form\Type\PreRegisterType;

class PreRegisterController extends Controller
{
    /**
     * @Route("/preregister", name="app_preregister")
     */
    public function preRegisterViewAction()
    {
        $record = new PreRegister();
        $user = $this->get('security.context')->getToken()->getUser();

        if (null === $user) {

            $form = $this->createForm(
                new PreRegisterType(),
                $record
            );

            return $this->render('TooBigAppBundle:PreRegister:phone_input.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            $this->get('flash_bag')->addMessage('Вы уже зарегистрированы и авторизованы!');
            return $this->redirect($this->generateUrl('app_main'));
        }
    }

    public function preRegisterPhoneAction(Request $request){
        $pre_register_model = $this->get('pre_register_model');
        $record = $pre_register_model->getPreRegisterByPhone($request->request->get('phone'));
        $user = $this->get('security.context')->getToken()->getUser();
        if (null === $user) {
            if ($request->isMethod('POST')) {
                $sms_code = $pre_register_model->generatePreRegisterCode();
                if (null === $record) {
                    try {
                        $record = new PreRegister();
                        $record->setCode($sms_code);
                        $pre_register_model->save($record);

                        /* Soap отправка кода на номер телефона */
                        return $pre_register_model->sendCodeWithSoap($record);

                    } catch (\Exception $e) {
                        return ['error' => 'Ошибка отправки SMS кода подтверждения!'];
                    }
                } else {
                    if (!$record->getStatus()){
                        $record->setCode($sms_code);
                        $pre_register_model->update($record);
                    } else {
                        /* TODO - проверка на регистрацию пользователя с этим номером */
                        return ['error' => 'Ваш номер уже подвержден!'];
                    }

                }
            }
        } else {
            return ['error' => 'Вы уже зарегистрированы и авторизованы!'];
        }


    }
}
