<?php
/**
 * 公用控制器方法 对外调用
 * @author Administrator
 *
 */
namespace  Multiple\Backend\Controllers;
use Multiple\Components\Base,
	Multiple\Library\Upload;
class CommonController extends Base{
	/**
	 * html5图片上传
	 * @return \Phalcon\Http\ResponseInterface
	 */
	public function indexAction(){
		$request=$this->request;
		if($request->isPost()){
			$upload=new Upload("img");
			$path=$upload->uploadFile();
			if($path===false){
				return $this->sendJson(array('status'=>0));
			}else{
				return $this->sendJson(array('status'=>1,'path'=>$path));
			}
		}
	}
}