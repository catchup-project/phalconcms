<?php
namespace Multiple\Models;
use Multiple\Library\Func;
class Role extends BaseModel{
	public function getSource()
	{
		return $this->tb_prefix."role";
	}
	
	/**
	 * 根据用户id得到角色名称的数组
	 * @param int $userId
	 * @return Ambigous <multitype:, multitype:unknown >
	 */
	public function getRoles($userId){
		$phql="select b.name as name from  \Multiple\Models\UserRole a join \Multiple\Models\Role b on a.role_id=b.id and a.user_id=".$userId;
		$res=$this->getModelsManager()->executeQuery($phql);
		$res=Func::toOneArray($res,'name');
		return $res;
	}
}