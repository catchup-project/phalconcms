<?php
namespace Multiple\Library;

use Phalcon\Mvc\User\Component,
			Phalcon\Acl as PhalconAcl,
		 	Phalcon\Acl\Adapter\Memory as AclMemory,
			Phalcon\Acl\Role as AclRole,
			Phalcon\Acl\Resource as AclResource,
			Phalcon\Mvc\Dispatcher,
			Multiple\Models\Node,
			Multiple\Models\RoleNode,
			Multiple\Models\Role;

/**
 * Library\Acl
 */
class Acl extends Component
{

	/**
	 * The ACL Object
	 *
	 * @var \Phalcon\Acl\Adapter\Memory
	 */
	private $acl;

	/**
	 * The filepath of the ACL cache file from APP_DIR
	 *
	 * @var string
	 */
	private $filePath = '/cache/acl/data.txt';

	/**
	 * Define the resources that are considered "private". These controller => actions require authentication.
	 * 控制器，方法 配置
	 * @var array
	 */
	private $privateResources ;
 	public   $publicResources = array('common' ,'login','index' );	
	
	/*public function __construct($dependencyInjector)
	{
		$this->_dependencyInjector = $dependencyInjector;
	}*/

	/**
	 * Returns the ACL list
	 * 获取 ACL 列表
	 * @return Phalcon\Acl\Adapter\Memory
	 */
	public function getAcl(){	
		// Check if the ACL is already created
		if (is_object($this->acl)) {
			return $this->acl;
		}
		// Check if the ACL is in APC
		if (function_exists('apc_fetch')) {
			$acl = apc_fetch('vokuro-acl');
			if (is_object($acl)) {
				$this->acl = $acl;
				return $acl;
			}
		}
		// Check if the ACL is already generated
		if (!file_exists(APP_DIR . $this->filePath)) {
			$this->acl = $this->rebuild();
			return $this->acl;
		}
		// Get the ACL from the data file
		$data = file_get_contents(APP_DIR . $this->filePath);
		$this->acl = unserialize($data);
		// Store the ACL in APC
		if (function_exists('apc_store')) {
			apc_store('vokuro-acl', $this->acl);
		}
		return $this->acl;
	}
	
	/**
	 * 得到所有的要受访问权限控制的节点
	 */
	public function setPrivateResource(){
		$parent=Node::find("level=2")->toArray();
		$controllers=array();
		foreach($parent as $parentItem){
			$node=explode('/',$parentItem['url']);
			$controller=$node[1];
			$action=$node[2];
			$this->privateResources[$controller][]=$action;
		}
	}

	/**
	 * Rebuilds the access list into a file
	 *
	 * @return \Phalcon\Acl\Adapter\Memory
	 */
	public function rebuild()
	{
		$acl = new AclMemory();
		$acl->setDefaultAction(\Phalcon\Acl::DENY);
		$roles = Role::find();
		//Adding Roles to the ACL
		foreach ($roles as $role) {
			$acl->addRole(new AclRole($role->name));
		}
		$this->setPrivateResource();
		//Adding Resources
		foreach ($this->privateResources as $resource => $actions) {
			$acl->addResource(new AclResource($resource), $actions);
		}
		$roleNode=new RoleNode();
		foreach($roles as $role){
			$nodes=$roleNode->getNodes($role->id);
			foreach($this->privateResources as $resource=>$actions){
				foreach($actions as $action){
					$url='admin/'.$resource.'/'.$action;
					if(in_array($url, $nodes)){
						$acl->allow($role->name, $resource, $action);
					}else{
						$acl->deny($role->name,$resource,$action);
					}
				}
			}
		}
		if (touch(APP_DIR . $this->filePath) && is_writable(APP_DIR . $this->filePath)) {
			file_put_contents(APP_DIR . $this->filePath, serialize($acl));
			// Store the ACL in APC
			if (function_exists('apc_store')) {
				apc_store('vokuro-acl', $acl);
			}
		} else {
			$this->flash->error(
				'The user does not have write permissions to create the ACL list at ' . APP_DIR . $this->filePath
			);
		}
		return $acl;
	}
	
	
	public function aclCheck(Dispatcher $dispatcher){
		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();
		$acl=$this->getAcl();
		$role=$this->session->get('roles');
		foreach($role as $itemRole){
			$allowed = $acl->isAllowed($itemRole, $controller, $action);
			if ($allowed != PhalconAcl::ALLOW) {
				return	$dispatcher->forward(array(
											'module'=>'admin',
											'controller' => 'index',
											'action' => 'premission'
											)
				);
			}
		}
		
	}
}
