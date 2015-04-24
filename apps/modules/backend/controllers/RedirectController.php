<?php
namespace  Multiple\Backend\Controllers;
use Multiple\Components\Base;
class RedirectController extends Base{
	/**
	 * 
	 * @param string $is_done
	 * @param string $redirect_url
	 * @param string $msg
	 * @param number $wait_second
	 */
	public function indexAction($is_done = 'false', $redirect_url = '', $msg = '',$wait_second = 3) {
		$this->view->setVar('is_done', $is_done);     //成功或失败('true','false')
		$this->view->setVar('redirect_url', $redirect_url);       //跳转地址
		$this->view->setVar('wait_second', $wait_second);     //跳转时间
		$d_msg = $is_done == 'true' ? '操作成功' : '操作失败';
		$msg = $msg ? $msg : $d_msg;
		$this->view->setVar('msg', $msg);     //提示信息
		$this->view->setMainView('redirect'); //直接加载views下的文件
	}
}