<?php

namespace Multiple\Frontend\Controllers;
use Multiple\Components\Base;
use Multiple\Models\Users;
use Phalcon\Http\Response;

class LoginController extends Base
{

    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction(){
        //echo md5('demo');die();
        //echo md5(md5(trim('demo')).'99999999');die();

        if ($this->request->isPost()) {
            $pwd = $this->request->getQuery("pwd", "string");
            $username = $this->request->getQuery("username", "string");
            $code = $this->request->getQuery("code", "string");

            if(empty($code) || empty($username) || empty($pwd)){
                $data = array("result"=>0,"msg"=>"用户名、密码、验证码不能为空！","success"=>false);
            }else{
                if($code != $this->session->get('captcha')){
                    $data = array("result"=>0,"msg"=>"验证码错误，请重试！","success"=>false);
                }else{
                    $userData = Users::findFirst("username = '".$username."'");
                    if($userData){
                        if($userData->password==md5(md5(trim($pwd)).$userData->encrypt)){
                            $data = array("result"=>1,"msg"=>"登陆成功！","success"=>false,"url"=>"saas/index");
                        }else{
                            $data = array("result"=>0,"msg"=>"密码错误，请重试！","success"=>false,"data"=>$userData);
                        }
                    }else{
                        $data = array("result"=>0,"msg"=>"用户名不存在，请重试！","success"=>false);
                    }
                }
            }
            $this->sendJson($data);
        }
    }


    /**
     * Allow a user to signup to the system
     */
    public function signupAction()
    {
        $form = new SignUpForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) != false) {

                $user = new Users();

                $user->assign(array(
                    'name' => $this->request->getPost('name', 'striptags'),
                    'email' => $this->request->getPost('email'),
                    'password' => $this->security->hash($this->request->getPost('password')),
                    'profilesId' => 2
                ));

                if ($user->save()) {
                    return $this->dispatcher->forward(array(
                        'controller' => 'index',
                        'action' => 'index'
                    ));
                }

                $this->flash->error($user->getMessages());
            }
        }

        $this->view->form = $form;
    }

    /**
     * Shows the forgot password form
     */
    public function forgotPasswordAction()
    {
        $form = new ForgotPasswordForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $user = Users::findFirstByEmail($this->request->getPost('email'));
                if (!$user) {
                    $this->flash->success('There is no account associated to this email');
                } else {

                    $resetPassword = new ResetPasswords();
                    $resetPassword->usersId = $user->id;
                    if ($resetPassword->save()) {
                        $this->flash->success('Success! Please check your messages for an email reset password');
                    } else {
                        foreach ($resetPassword->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                }
            }
        }

        $this->view->form = $form;
    }

    /**
     * Closes the session
     */
    public function logoutAction()
    {
        $this->auth->remove();

        return $this->response->redirect('index');
    }
}