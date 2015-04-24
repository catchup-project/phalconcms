<?php
/**
 * 后台会员控制 器
 * @author chenlin
 *
 */
namespace Multiple\Backend\Controllers;
use Phalcon\Cache\Multiple;
use Multiple\Components\Base,
	Multiple\Library\Upload,
	Multiple\Models\Member as Member;

use Multiple\Library\Pagination as Pagination;  //调用自己写的分页类；

class MemberController extends Base
{

	public function indexAction()
	{
		$member=new Member();
		$total=$member->count();
		$pagination=new Pagination($total,$this->pageSize);
		$page=$pagination->fpage();
		$users=$member->find(array('limit'=>array('number'=>$this->pageSize,'offset'=>$pagination->offset)));
		$this->view->setVar("page", $page);
		$this->view->setVar("users", $users);
		$this->view->setVar("total",$total);
	}

    	public function addAction(){
			$request=$this->request;
    		if($request->isPost()){
				if($_FILES['photo']['name']){
					$upload=new Upload("photo");
					$path=$upload->uploadFile();
					if($path===false){
						return $this->sendJson(array('status'=>0));
					}
					$_POST['phone']=$path;
				}
	    		$member=new Member();
	    		if(!$member->saveField($_POST)){
	    			$status=0;
	    		}else{
	    			$status=1;
	    		}
	    		return $this->sendJson(array('status'=>$status,'info'=>'添加成功','location'=>'/admin/member/index'));
    		}
   	 }
   	 
   	 /**
   	  * 会员的状态开关
   	  * @param number $status
   	  * @param unknown $id
   	  */
   	 public function statusAction($status=0,$id){
   	 	$member=Member::findFirstById(intval($id));
   	 	$member->status=$status;
   	 	if($member->save()){
	   	 	return $this->sendJson(array('status'=>1));
   	 	}else{
   	 		return $this->sendJson(array('status'=>0));
   	 	}
   	 }
   	 
   	 public function editAction($id){
   	 	$member=Member::findFirstById($id);
   	 	$result=array('status'=>0,'info'=>'修改失败');
   	 	if(!$member){
   	 		return $this->sendJson($result);
   	 	}
   	 	$request=$this->request;
   	 	if($request->getPost()){
   	 		if($_FILES['photo']['name']){
   	 			$upload=new Upload("photo");
   	 			$path=$upload->uploadFile();
   	 			if($path===false){
   	 				return $this->sendJson($result);
   	 			}
   	 			$_POST['phone']=$path;
   	 		}
   	 		if(!$member->save($_POST)){
	    			return $this->sendJson($result);
	    	}
	    	$result=array('status'=>1,'info'=>'修改成功','location'=>'/admin/member/index');
	    	return $this->sendJson($result);
   	 	}else{
	   	 	$this->view->setVar("member",$member->toArray());
   	 	}
   	 }
   	 
   	 public function delAction($id){
   	 	if(strpos($id,',')===false){
	   	 	$member=Member::findFirstById($id);
	   	 	$member->delete();
   	 	}else{
   	 		$phql="delete from \Multiple\Models\Member where id in($id)";
   	 		$this->modelsManager->executeQuery($phql);
   	 	}
   	 	return $this->sendJson(array('status'=>1));
   	 }

}