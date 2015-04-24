<?php
namespace Multiple\Models;
use Multiple\Library\Func,
	Phalcon\Mvc\User\Component;
class Log extends BaseModel{
	public function getSource()
	{
		return $this->tb_prefix."log";
	}
	
	/**
	 * 
	 * @param int $type 2表示操作日志 1登录日志
	 * @param $info
	 */
	public function addData($type,$info){
		$component=new Component();
		$user=$component->session->get('auth');
		$data=array('type'=>$type,'info'=>$info,'addtime'=>time(),'ip'=>Func::getip(),'user_id'=>$user['id']);
		return self::save($data);
	}
}