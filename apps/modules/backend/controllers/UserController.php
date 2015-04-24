<?php
namespace  Multiple\Backend\Controllers;
use Multiple\Components\Base,
	Multiple\Models\User,
	Multiple\Models\UserRole,
	Multiple\Models\Role;
class UserController extends  Base{
	public function indexAction(){
		$users=User::find()->toArray();
		$this->view->setVar("users",$users);
	}
	public function addAction(){
		$request=$this->request;
		if($request->isPost()){
			$user=new User();
			$user->sort=$request->getPost("sort",'int');
			$user->phone=$request->getPost("phone",'int');
			$user->email=$request->getPost("email",'int');
			$user->encryption=rand(1000,100000);
			$user->username=$request->getPost("username",'string');
			$user->password=md5($request->getPost('password','string').$user->encryption);
			$this->db->begin();
			$result=array('status'=>0,'info'=>'添加失败',"location"=>"/admin/user/index");
			if($user->save()){
				$roleIds=$request->getPost("role_id");
				foreach($roleIds as $roleid){
					$userRole=new UserRole();
					$userRole->user_id=$user->id;
					$userRole->role_id=$roleid;
					if(!$userRole->save()){
						$this->db->rollback();
						return $this->sendJson($result);
					}
				}
			}else{
				$this->db->rollback();
				return $this->sendJson($result);
			}
				$this->db->commit();
				$result=array('status'=>1,'info'=>'添加成功',"location"=>"/admin/user/index");
				return $this->sendJson($result);
		}
		$roles=Role::find()->toArray();
		$this->view->setVar('roles',$roles);
	}
	
	public function editAction($id){
		$request=$this->request;
		$user=User::findFirstById($id);
		if(!$user){
			return $this->forward("user/index");
		}
		$roles=Role::find()->toArray();
		//查找 当前管理员的角色
		$userRole=UserRole::find(array(
				"conditions"=>"user_id=:user_id:","columns"=>"role_id","bind"=>array('user_id'=>$id)))->toArray();
		$userRole=$this->toOneArray($userRole,'role_id');
		if($request->isPost()){
			$user->sort=$request->getPost("sort",'int');
			$user->phone=$request->getPost("phone",'int');
			$user->email=$request->getPost("email",'string');
			if($request->getPost('password','string')){
				$user->password=md5($request->getPost('password','string').$user->encryption);
			}
			$user->save();
			$postRoles=$request->getPost("role_id","string");
			$this->role($userRole, $postRoles, $id);
			return $this->sendJson(array('status'=>1,'info'=>'修改成功',"location"=>"/admin/user/index"));
		}
		$this->view->setVar("userRole", $userRole);
		$this->view->setVar('roles',$roles);
		$this->view->setVar('user',$user->toArray());
	}
	
	
	public function delAction($id){
		$request=$this->request;
		$status=0;
		if(strpos($id,',')!==false){
			$phql="delete from \Multiple\Models\User where id in( $id)";
			$result=$this->modelsManager->executeQuery($phql);
			if($result)$status=1;
		}else{
			$user=User::findFirstById($id);
			if($user){
				if($user->delete()){
					$status=1;
				}
			}
		}
		return $this->sendJson(array('status'=>$status));
	}
	
	public function statusAction($status,$id){
		$user=User::findFirstById($id);
		if(!$user){
			return $this->forward("user/index");
		}
		$user->status=$status;
		if(!$user->save()){
			$status=0;
		}else{
			$status=1;
		}
		return $this->sendJson(array('status'=>$status));
	}
	
	/**
	 * 管理员角色处理
	 */
	protected function role($hasroles,$postRoles,$id){
		if(count($postRoles)>0){//把提交过来的新增加的做处理
			foreach($postRoles as $ids){
				if(count($hasroles)<1||!in_array($ids,$hasroles)){
					$roleNode=new UserRole();
					$roleNode->role_id=$ids;
					$roleNode->user_id=$id;
					$roleNode->save();
				}
			}
		}
		if(count($hasroles)>0){
			foreach($hasroles as $item){//如果以前的节点没有在新添加过来的节点。则删除
				if(!in_array($item,$postRoles)){
					$phql="delete from \Multiple\Models\UserRole where user_id=".$id." and role_id=".$item;
					$re=$this->modelsManager->executeQuery($phql);
				}
			}
		}
	}
}