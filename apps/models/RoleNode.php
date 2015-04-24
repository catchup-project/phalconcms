<?php
namespace Multiple\Models;
use Multiple\Library\Func;
class RoleNode extends BaseModel{
	public function getSource(){
		return $this->tb_prefix."role_node";
	}
	
	/*
	 * 得到一个角色所拥有的节点
	 */
	public function getNodes($role_id){
		$phql="select b.url as url from \Multiple\Models\RoleNode as a join \Multiple\Models\Node as b on a.node_id=b.id and a.role_id=".$role_id;
		$result=$this->getModelsManager()->executeQuery($phql);
		return Func::toOneArray($result,'url');
	}
}