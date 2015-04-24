<?php
/**
 * 文章控制器
 * @author chenlin
 *
 */
namespace  Multiple\Backend\Controllers;
use Multiple\Components\Base,
		Multiple\Models\ConfigGroup;
class ConfigGroupController extends Base{
	public function indexAction(){
		$configGroups=ConfigGroup::find()->toArray();
		$this->view->setVar("configGroups",$configGroups);
	}
	
	
	public function addAction(){
		$request=$this->request;
		if($request->isPost()){
			$configGroup=new ConfigGroup();
			$res=$configGroup->saveField($_POST);
			$result=array('status'=>0,'info'=>'添加失败');
			if($res===true){
				$result=array('status'=>1,'info'=>'添加成功','location'=>'/admin/configGroup/index');
			}
			return $this->sendJson($result);
		
		}
		$configGroup=ConfigGroup::find()->toArray();
		$this->view->setVar("configGroup", $configGroup);
	}
	
	
	public function editAction($id){
		$configGroup=ConfigGroup::findFirstById($id);
		$request=$this->request;
		if($request->isPost()){
			if($configGroup->save($_POST)){
				$result=array('status'=>1,'info'=>'修改成功','location'=>'/admin/configGroup/index');
			}
			return $this->sendJson($result);
		}
		$this->view->setVar("configGroup",$configGroup->toArray());
	}
	
	
	
	public function delAction($id){
		$result=array('status'=>0);
		$phql="delete from Multiple\\Models\\Article where id in(".$id.")";
		$res=$this->modelsManager->executeQuery($phql);
		if($res){
				$result=array('status'=>1,'location'=>'/admin/configGroup/index');
		}
		return $this->sendJson($result);
	}
}