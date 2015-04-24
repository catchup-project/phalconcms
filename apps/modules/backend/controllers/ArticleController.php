<?php
/**
 * 文章控制器
 * @author chenlin
 *
 */
namespace  Multiple\Backend\Controllers;
use Multiple\Components\Base,
		Multiple\Models\Article,
		Multiple\Models\Channel,
		Multiple\Models\User,
		Multiple\Models\Category;
class ArticleController extends Base{
	public function indexAction(){
		$articles=Article::find()->toArray();
		$this->view->setVar("articles",$articles);
	}
	
	
	public function addAction(){
		$request=$this->request;
		if($request->isPost()){
			$article=new Article();
			$res=$article->saveField($_POST);
			$result=array('status'=>0,'info'=>'添加失败');
			if($res===true){
				$result=array('status'=>1,'info'=>'添加成功','location'=>'/admin/article/index');
			}
			return $this->sendJson($result);
		}
		$channels=Channel::find()->toArray();
		$categories=Category::find()->toArray();
		$users=User::find()->toArray();
		$this->view->setVar("channels", $channels);
		$this->view->setVar("categories",$categories);
		$this->view->setVar("users",$users);
	}
	
	
	public function editAction($id){
		$article=Article::findFirstById($id);
		$request=$this->request;
		if($request->isPost()){
			$pitrue=$this->request->getPost('picture','string');
			$result=array('status'=>0,'info'=>'修改失败');
			if(empty($pitrue)){
				unset($_POST['picture']);
			}
			if($article->save($_POST)){
				$result=array('status'=>1,'info'=>'修改成功','location'=>'/admin/article/index');
			}
			return $this->sendJson($result);
		}
		$channels=Channel::find()->toArray();
		$categories=Category::find()->toArray();
		$this->view->setVar("article", $article->toArray());
		$this->view->setVar("categories",$categories);
		$this->view->setVar("channels",$channels);
	}
	
	
	
	public function delAction($id){
		$result=array('status'=>0);
		if(strpos($id,',')===false){
			$article=Article::findFirstById($id);
			if($article->delete()){
				$result=array('status'=>1,'location'=>'/admin/article/index');
			}
		}else{
			$phql="delete from Multiple\Models\Article where id in(".$id.")";
			$res=$this->modelsManager->executeQuery($phql);
			if($res){
				$result=array('status'=>1,'location'=>'/admin/article/index');
			}
		}
		return $this->sendJson($result);
	}
}