<?php
namespace Multiple\Components;
use Phalcon\Mvc\Controller,
	Phalcon\Mvc\Dispatcher,
	Multiple\Models\Log,
	Multiple\Library\Acl;

class Base extends Controller
{
	protected $pageSize=10;
    /**
     * Execute before the router so we can determine if this is a provate controller, and must be authenticated, or a
     * public controller that is open to all.
     *
     * @param Dispatcher $dispatcher
     * @return boolean
     */

    public function beforeExecuteRoute(Dispatcher $dispatcher) {
        \Phalcon\Tag::setTitleSeparator('·');
        \Phalcon\Tag::setTitle('XShop');
        $controllerName = $dispatcher->getControllerName();
		if(!$this->session->get('auth')){
			return $this->forward("login/index");
		}
		if($this->session->get('auth')['id']!=1){
			$acl=new Acl();
	        // Only check permissions on private controllers
	        if (!in_array($controllerName, $acl->publicResources)) {
				return $acl->aclCheck($dispatcher);
	        }
		}

    }



    /**
     * 设置默认标题；
     */
    protected function initialize()
    {
        $this->tag->prependTitle('思派（北京）网络科技有限公司');
    }

    /**
     * @param $uri
     * @return mixed
     */
    protected function forward($uri)
    {
        $uriParts = explode('/', $uri);
        return $this->dispatcher->forward(
            array(
            	'module'=>'admin',
                'controller' => $uriParts[0],
                'action' => $uriParts[1]
            )
        );
    }
    
 	public function redirect($redirect=array()){
 		$this->dispatcher->forward(
 				array(
 					'controller'=>'redirect', 
 					'action'=>'index',
         			 'params'=>array(
         			 		'is_done'=>$redirect['is_done'],
         			 		'redirect_url'=>$redirect['redirect_url'],
         			 		'msg'=>$redirect['msg'])));
	
 	}

    /**
     * @param $data
     *
     */
    protected function sendJson($data){
        $this->response->setHeader("Content-Type","text/json");
        if(is_array($data)){
            $this->response->setContent(json_encode($data, JSON_NUMERIC_CHECK)); //json_encode(Robots::findFirst()->toArray(), JSON_NUMERIC_CHECK)
        }else{
            $this->response->setContent($data); //json_encode(Robots::findFirst()->toArray(), JSON_NUMERIC_CHECK)
        }
        $this->addLog($data['status']);
        return $this->response->setJsonContent($data);
    }

    /**
     * 添加 session
     */
    protected function addSession(){
        $this->session->set("session_name", "session_value");
    }

    /**
     * 获取 session
     */
    protected function getSession(){
        $this->session->get("session-name");
    }

    /**
     * 删除 session
     */
    protected function removeSession(){
        $this->session->remove("session-name");
    }

    /**
     * 销毁 session
     */
    protected function destroySession(){
        $this->session->destroy();
    }



    /* 检查所填验证码是否跟session里验证码一致 */
    public function codeCheckAction(){
        if ($this->request->isPost() && $this->request->isAjax() ) {
            $filter = new Filter();
            $code = $filter->sanitize($this->request->getPost('code'), "string");

            if($code == $this->session->get('captcha') ?TRUE:FALSE){
                $data = array('success'=>'1');
            }else{
                $data = array('success'=>'0');
            }
        }
        $this->sendJson($data);
    }
    
    /**
     * 
     * @param unknown Phalcon\Mvc\Model\Resultset\Simple|array对象数组
     */
    protected function toOneArray($obj,$column=null){
    	$arr=array();
    	if(count($obj)){
    		foreach($obj as $v){
    			if($column!==null){
    				$arr[]=$v[$column];
    			}else{
	    			$arr[]=$v;
    			}
    		}
    	}
    	return $arr;
    }
    
    /**
     * 修改模型对应的属性
     * @param unknown $arr
     * @param unknown $model
     * @return boolean
     */
    public function attrModel($arr=array(),$model){
    	if(!is_array($arr)||!is_object($model)){
    		return false;
    	}
    	foreach($arr as $colume=>$value){
    		$model->$colume=$value;
    	}
    	if(!$model->save()){
    		return false;
    	}
    	return true;
    }
   
    public function addLog($status){
    	$controllers=array('user'=>'管理员','member'=>'会员','role'=>'角色','node'=>'节点','article'=>'文章','category'=>'栏目','channel'=>'频道');
	    $controller=array_keys($controllers);
    	$controllerName=$this->dispatcher->getControllerName();
	    if(!in_array($controllerName,$controller)){
	    	return true;
	    }
	    $actions=array('del'=>'删除','add'=>'添加','edit'=>'修改','status'=>'禁用或者启用');
	    $action=array_keys($actions);
    	$actionName=$this->dispatcher->getActionName();
    	if(!in_array($actionName,$action)){
    		return true;
    	}
    	$log=new Log();
    	$msg=($status==1)?'成功':'失败';
    	return $log->addData(2,$controllers[$controllerName].$actions[$actionName].$msg);
    }
}
