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

        if( !$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){

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

    /**
     * @param Request $request
     * @return array
     */
    public function preRegisterPhoneAction(Request $request){

        if (strlen($request->request->get('PreRegister')['phone']) == 0) {
            return new Response('Необходимо указать номер телефона в правильном формате!');
        }

        $pre_register_model = $this->get('pre_register_model');
        $record = $pre_register_model->getPreRegisterByPhone($request->request->get('PreRegister')['phone']);

        if( !$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            if ($request->isMethod('POST')) {
                $sms_code = $pre_register_model->generatePreRegisterCode();
                if (null === $record) {
                    try {
                        $record = new PreRegister();
                        $record->setCode($sms_code);
                        $record->setPhone($request->request->get('PreRegister')['phone']);
                        $pre_register_model->save($record);

                        /* Soap отправка кода на номер телефона */
                        $pre_register_model->sendCodeWithSoap($record);
                        return $this->render('TooBigAppBundle:PreRegister:check_code.html.twig');

                    } catch (\Exception $e) {
                        return new Response('Ошибка отправки SMS кода подтверждения!');
                    }
                } else {
                    if (!$record->getStatus()){
                        $record->setCode($sms_code);
                        $pre_register_model->update($record);
                        /* Soap отправка кода на номер телефона */
                        $pre_register_model->sendCodeWithSoap($record);
                        return $this->render('TooBigAppBundle:PreRegister:check_code.html.twig');
                    } else {
                        $user = $this->container->get('fos_user.user_manager')->findUserBy(['phone' => $record->getPhone()]);
                        if ( null !== $user){
                            return $this->render('TooBigAppBundle:PreRegister:existing_register.html.twig');
                        } else {
                            $_SESSION['register_phone'] = $record->getPhone();
                            return $this->render('TooBigAppBundle:PreRegister:continue_register.html.twig');
                        }
                    }
                }
            }
        } else {
            return ['error' => 'Вы уже зарегистрированы и авторизованы!', 'error_code' => 101 ];
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function preCheckPhoneAction(Request $request){

        if( !$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){

            $pre_register_model = $this->get('pre_register_model');
            $record = $pre_register_model->getPreRegisterByPhone($request->request->get('PreRegister')['phone']);

            if ($request->isMethod('POST')) {
                if (null === $record) {
                    return ['error' => 'Нет такого номера', 'error_code' => 102];
                } else {
                    if (!$record->getStatus()){
                        if ((int)$request->request->get('PreRegister')['code'] === $record->getCode()){
                            $record->setStatus(true);
                            $pre_register_model->update($record);
                            $_SESSION['register_phone'] = $record->getPhone();
                            return $this->render('TooBigAppBundle:PreRegister:start_register.html.twig');
                        } else {
                            return new Response('Неверно указан проверочный код SMS!');
                        }
                    } else {
                        /* TODO - проверка на регистрацию пользователя с этим номером */
                        return ['error' => 'Номер уже подвержден!', 'error_code' => 100];
                    }
                }
            }
        } else {
            return ['error' => 'Вы уже зарегистрированы и авторизованы!', 'error_code' => 101];
        }


    }
}
