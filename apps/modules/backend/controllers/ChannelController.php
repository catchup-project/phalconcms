<?php
namespace  Multiple\Backend\Controllers;
use Multiple\Components\Base,
		Multiple\Models\Channel;
class ChannelController extends Base{
	public function indexAction(){
		$channels=Channel::find()->toArray();
		$this->view->setVar("channels", $channels);
	}
	public function addAction(){
		$request=$this->request;
		if($request->isPost()){
			$channel=new Channel();
			$result=array('status'=>0,'info'=>'添加失败');
			if($channel->saveField($_POST)){
				$result=array('status'=>1,'info'=>'添加成功','location'=>'/admin/channel/index');
			}
			return $this->sendJson($result);
		}
	}
	public function editAction($id){
		$channel=Channel::findFirstById($id);
		$request=$this->request;
		if($request->isPost()){
			$result=array('status'=>0,'info'=>'修改失败');
			if($this->attrModel($_POST,$channel)){
				$result=array('status'=>1,'info'=>'修改成功','location'=>'/admin/channel/index');
			}
			return $this->sendJson($result);
		}
		$this->view->setVar("channel", $channel->toArray());
	}
	public function delAction($ids){
		if($this->request->isPost()){
			if(strpos($ids,',')===false){
				$channel=Channel::findFirstById($ids);
				$status=0;
				if($channel->delete()){
					$status=1;
				}
				return $this->sendJson(array('status'=>$status));
			}
		}
	}
}