<?php
namespace  Multiple\Backend\Controllers;
use Multiple\Components\Base,
	Multiple\Models\Category,
	Multiple\Models\Channel;

class CategoryController extends Base{
	public function indexAction(){
			$channels=Channel::find()->toArray();
			$categories=Category::find()->toArray();
			$this->view->setVar("channels", $channels);
			$this->view->setVar("categories", $categories);
	}
	public function addAction(){
		$request=$this->request;
		if($request->isPost()){
			$category=new Category();
			$data=array('status'=>0);
			if($category->saveField($_POST)){
				$data=array('status'=>1,'info'=>'添加成功','location'=>'/admin/category/index');
			}
			return $this->sendJson($data);
		}
		$channels=Channel::find()->toArray();
		$this->view->setVar("channels", $channels);
	}
	public function editAction($id){
	
	}
}