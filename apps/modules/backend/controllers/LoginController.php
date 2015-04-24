<?php

namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Controller,
		Multiple\Library\Func,
		 Multiple\Models\User,
		 Multiple\Models\Role,
		 Phalcon\Mvc\View;

class LoginController extends Controller
{

    public function initialize()
    {
        //$this->setTitle('Edit User')->setDescription('Edit this user.');
    }


    public function indexAction(){
        if ($this->request->isPost()) {
            $pwd = $this->request->getPost("password", "string");
            $username = $this->request->getPost("username", "string");
            $code = $this->request->getPost("code", "string");
            if(empty($code) || empty($username) || empty($pwd)){
                $data = array("result"=>0,"msg"=>"用户名、密码、验证码不能为空！","success"=>false);
            }else{
                if($code != $this->session->get('captcha')){
                    $data = array("result"=>0,"msg"=>"验证码错误，请重试！","success"=>false);
                }else{
                    $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
                    if ( preg_match( $pattern, $username ) ){
                        $userData = User::findFirstByEmail($username);
                    }else{
                        $userData = User::findFirstByUsername($username);
                    }
                    if($userData){
                        if($userData->password==md5(trim($pwd).$userData->encryption)){
                        		$userData=$userData->toArray();
                        		$role=new Role();
                        		$roles=$role->getRoles($userData['id']);
                        		$this->session->set("auth",$userData);
                        		$this->session->set('roles',$roles);
                            	$data = array("msg"=>"登陆成功！","status"=>1,"url"=>"/admin/index/index");
                        }else{
                            $data = array("msg"=>"密码错误，请重试！","status"=>0);
                        }
                    }else{
                        $data = array("msg"=>"用户名不存在，请重试！","status"=>0);
                    }
                }
            }
          return $this->response->setJsonContent($data);
        }
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }
    
    /* 验证码生成函数 */
    public function codeAction() {
    	ob_start();//打开资源
    	$this->session->set('captcha', $this->captcha->get_captcha_value());
    	$this->captcha->output();
    	ob_flush();//关闭资源
    }

    /**
     * 用户退出
     */
    public function logoutAction(){
        $this->session->remove('auth');
         return $this->dispatcher->forward( 
         		array(
            		'module'=>'admin',
                	'controller' => 'login',
                	'action' =>'index'
            	)
        );
    }
}