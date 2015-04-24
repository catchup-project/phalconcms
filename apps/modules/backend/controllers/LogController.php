<?php
/**
 * 日志管理
 * @author Administrator
 *
 */
namespace  Multiple\Backend\Controllers;
use Multiple\Components\Base,
	Multiple\Models\Log,
	Multiple\Library\Pagination as Pagination;  //调用自己写的分页类；

class LogController extends Base{
	public function indexAction(){
		$log=new Log();
		$total=$log->count();
		$pagination=new Pagination($total,$this->pageSize);
		$page=$pagination->fpage();
		$queryBuilder=$this->modelsManager->createBuilder()
		->from(array('a'=>'Multiple\Models\Log'))
		->leftJoin('Multiple\Models\User','a.user_id=b.id','b');
		$queryBuilder->columns(array(
				'addtime'=>'a.addtime',
				'type'=>'a.type',
				'info'=>'a.info',
				'id'=>'a.id',
				'username'=>'b.username'
		));
		$logs=$queryBuilder->limit($this->pageSize,$pagination->offset)->getQuery()->execute();
		$this->view->setVar("page", $page);
		$this->view->setVar("logs", $logs->toArray());
		$this->view->setVar("total",$total);
	}
	
	public function delAction($id){
		$result=array('status'=>0);
		if(strpos($id,',')===false){
			$article=Log::findFirstById($id);
			if($article->delete()){
				$result=array('status'=>1,'location'=>'/admin/log/index');
			}
		}else{
			$phql="delete from Multiple\Models\Log where id in(".$id.")";
			$res=$this->modelsManager->executeQuery($phql);
			if($res){
				$result=array('status'=>1,'location'=>'/admin/log/index');
			}
		}
		return $this->sendJson($result);
	}
}
