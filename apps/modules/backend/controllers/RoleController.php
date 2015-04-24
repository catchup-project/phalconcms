<?php
/**
 * 角色管理
 * @author chenlin
 *
 */
namespace  Multiple\Backend\Controllers;
use Multiple\Components\Base,
		Multiple\Models\Node,
		Multiple\Models\RoleNode,
		\Phalcon\Mvc\Model\Resultset,
		Multiple\Models\Role;
class RoleController extends Base{
	public function indexAction(){
		$role=new Role();
		$roles=$role->find();
		$this->view->setVar("roles",$roles);
	}
	public function addAction(){
		$request=$this->request;
		if($request->isPost()){
			$role=new Role();
			$role->name=$request->getPost("name","string");
			$role->description=$request->getPost("description",'string');
			$status=0;
			if($role->save()){
				$status=1;
			}
			return $this->sendJson(array('status'=>$status,'location'=>'/admin/role'));
		}
	}
	public function editAction($id){
		$role=Role::findFirstById($id);
		if(empty($role)){
			return 	parent::redirect(array('msg'=>'数据不存在','is_done'=>false));
		}
		$reqeust=$this->request;
		if($reqeust->isPost()){
			$role->name=$reqeust->getPost("name","string");
			$role->description=$reqeust->getPost("description","string");
			$result=array('status'=>1,'info'=>'操作成功','location'=>'/admin/role');
			if(!$role->save()){
				$result=array('status'=>0,'info'=>'修改失败');
			}
			return $this->sendJson($result);
		}
		$this->view->setVar("role", $role->toArray());
	}
	
	/**
	 * 检查角色名称不能重复
	 */
	public function checkNameAction(){
		if($this->request->isAjax()){
			$name=$this->request->getPost("name","string");
			$param=$this->request->getPost("param","string");
			$role=Role::findFirst("$name='$param'");
			$result=array('status'=>1,'info'=>'');
			if($role){
				$result=array('status'=>0,'info'=>'角色名字已经被使用');
			}
			return $this->sendJson($result);
		}
	}
	
	/**
	 *批量删除与单个删除 
	 * @param mix $id
	 */
	public function delAction($id){
		$status=0;
		if(strpos($id,',')===false){
			$role=Role::findFirstById($id);
			if($role->delete()){
				$status=1;
			}
		}else{
			$phql="delete from \Multiple\Models\Role where id in( $id)";
			$result=$this->modelsManager->executeQuery($phql);
			if($result)$status=1;
		}
		return $this->sendJson(array('status'=>$status));
	}
	
	/**
	 * 为角色分配权限
	 * @param unknown $id
	 */
	public function nodeAction($id){
		$request=$this->request;
		$id=intval($id)+0;
		$phql="select b.id as id from  \Multiple\Models\RoleNode a left join \Multiple\Models\Node b on a.node_id=b.id where a.role_id=".$id;
		$res=$this->modelsManager->executeQuery($phql);
		$res->setHydrateMode(Resultset::HYDRATE_ARRAYS);
		$hasNodes=($this->toOneArray($res,'id'));//这个角色已经有的节点
		if($request->isPost()){
						$postNodes=$request->getPost("ids");
						if(count($postNodes)>0){//把提交过来的新增加的做处理
							foreach($postNodes as $ids){
								if(count($hasNodes)<1||!in_array($ids,$hasNodes)){
									$roleNode=new RoleNode();
									$roleNode->role_id=$id;
									$roleNode->node_id=$ids;
									$roleNode->save();
								}
							}
						}
						if(count($hasNodes)>0){
								foreach($hasNodes as $item){//如果以前的节点没有在新添加过来的节点。则删除
									if(!in_array($item,$postNodes)){
										$phql="delete from \Multiple\Models\RoleNode where role_id=".$id." and node_id=".$item;
										$re=$this->modelsManager->executeQuery($phql);
									}
								}
						}
						return $this->sendJson(array('status'=>1,"info"=>'修改成功','location'=>'/admin/role/index'));
		}else{
			$arr=Node::getAllNode();
			$this->view->setVar("nodes", $hasNodes);
			$this->view->setVar("arr", $arr);	//所有节点
		}
	}
}