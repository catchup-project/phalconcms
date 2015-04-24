<?php

namespace Multiple\Frontend\Controllers;
use Multiple\Components\Base;
use Multiple\Models\Users;
use Phalcon\Http\Response;
use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclMemory;

class IndexController extends Base
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


    public function demoAction(){
        echo 999;die();
    }

    public function testAction(){
        $acl = new AclMemory();
        $acl->setDefaultAction(Acl::DENY);

        $acl->addRole('user');
        $acl->addRole('admin', 'user');
        $acl->addRole('developer', 'admin');

        $acl->addResource('tickets', array('insert', 'update', 'delete'));

        $acl->allow('user', 'tickets', 'insert');

        $acl->deny('guests', 'tickets', 'insert');

        var_dump($acl->isAllowed('user', 'tickets', 'insert')); // returns 1
        var_dump($acl->isAllowed('admin', 'tickets', 'insert')); // returns 1
        var_dump($acl->isAllowed('developer', 'tickets', 'insert')); // returns 0 (!)
        echo 8888;die();

    }

    /* 验证码生成函数 */
    public function codeAction() {
        ob_start();//打开资源
        $this->session->set('captcha', $this->captcha->get_captcha_value());
        $this->captcha->output();
        ob_flush();//关闭资源
    }

    /* 检查所填验证码是否跟session里验证码一致 */
    public function codeCheckAction(){
        if ($this->request->isPost() && $this->request->isAjax() ) {
            $filter = new Filter();
            $code = $filter->sanitize($this->request->getPost('code'), "string");

            if($code == $this->session->get('captcha') ?TRUE:FALSE){
                $data = array('success'=>'1');
            }else{
                $data = array('success'=>'0');
            }
        }
        $this->sendJson($data);
    }

}