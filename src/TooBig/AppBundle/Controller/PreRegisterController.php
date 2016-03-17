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
                        $response = new JsonResponse();
                        $response->setData(['response' => preg_replace('/[\r\n]/i','',$this->render('TooBigAppBundle:PreRegister:check_code.html.twig', ['test_code' => $sms_code])->getContent()),'status'=>'202']);
                        return $response;

                    } catch (\Exception $e) {
                        return new Response('Ошибка отправки SMS кода подтверждения!');
                    }
                } else {
                    if (!$record->getStatus()){
                        $record->setCode($sms_code);
                        $pre_register_model->update($record);
                        /* Soap отправка кода на номер телефона */
                        $pre_register_model->sendCodeWithSoap($record);
                        $response = new JsonResponse();
                        $response->setData(['response' => preg_replace('/[\r\n]/i','',$this->render('TooBigAppBundle:PreRegister:check_code.html.twig', ['test_code' => $sms_code])->getContent()),'status'=>'202']);
                        return $response;
                    } else {
                        /* попадает ответ в тот же check_code, исправить */
                        $user = $this->container->get('fos_user.user_manager')->findUserBy(['phone' => $record->getPhone()]);
                        if ( null !== $user){
                            $response = new JsonResponse();
                            $response->setData(['response' => preg_replace('/[\r\n]/i','',$this->render('TooBigAppBundle:PreRegister:existing_register.html.twig')->getContent()),'status'=>'201']);
                            return $response;
                        } else {
                            $_SESSION['register_phone'] = $record->getPhone();
                            $_SESSION['register_code'] = $record->getCode();
                            $response = new JsonResponse();
                            $response->setData(['response' => preg_replace('/[\r\n]/i','',$this->render('TooBigAppBundle:PreRegister:continue_register.html.twig')->getContent()),'status'=>'200']);
                            return $response;
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
                            $_SESSION['register_code'] = $record->getCode();
                            return $this->render('TooBigAppBundle:PreRegister:start_register.html.twig');
                        } else {
                            return $this->render('TooBigAppBundle:PreRegister:check_code_error.html.twig');
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
