<?php
/**
 * 节点控制器
 * @author chenlin
 *
 */
namespace  Multiple\Backend\Controllers;
use Multiple\Components\Base,
	Multiple\Models\Node;
class NodeController extends  Base{
	public function indexAction(){
		$count=Node::count();
		$arr=Node::getAllNode();
		$this->view->setVar("count", $count);
		$this->view->setVar('node', $arr);
	}
	public function addAction(){
		$request=$this->request;
		$parent=Node::find("level=1");//查找 根节点
		if($request->isPost()){
			$node=new Node();
			$node->title=$this->request->getPost("title",'string');
			$node->parent_id=$this->request->getPost("parent_id","int");
			$node->url=$this->request->getPost("url","string");
			$node->sort=$this->request->getPost("sort",'int');
			$node->is_main=$this->request->getPost("is_main","int");
			if($node->parent_id!=0){
				$node->level=2;
			}else{
				$node->level=1;
			}
			$result=array('status'=>1,'location'=>'/admin/node');
			if(!$node->save()){
				$result=array('status'=>0,'info'=>'添加失败');
			}
			return $this->sendJson($result);
		}else{
			$this->view->setVar("parent", $parent);
		}
	}
	public function editAction($id){
		$node=Node::findFirstById($id);
		if(!$node){
			return $this->forward("node/index");
		}
		$request=$this->request;
		if($request->isPost()){
			$node->title=$this->request->getPost("title",'string');
			$node->parent_id=$this->request->getPost("parent_id","int");
			$node->url=$this->request->getPost("url","string");
			$node->sort=$this->request->getPost("sort",'int');
			$node->is_main=$this->request->getPost("is_main","int");
			if($node->parent_id!=0){
				$node->level=2;
			}else{
				$node->level=1;
			}
			$result=array('status'=>1,'location'=>'/admin/node');
			if(!$node->save()){
				$result=array('status'=>0,'info'=>'修改失败');		
			}
			return $this->sendJson($result);
		}
		$parent=Node::find("level=1")->toArray();
		$this->view->setVar("parent", $parent);
		$this->view->setVar("node", $node->toArray());
	}
	
	public function delAction($id){
			$id=trim($id,',');
			if($id){
				$phql="delete from \Multiple\Models\Node where id in( $id)";
				$result=$this->modelsManager->executeQuery($phql);
				$status=0;
				if($result){
					$status=1;
				}
				return $this->sendJson(array('status'=>$status));
			}
	}
	
	
}
