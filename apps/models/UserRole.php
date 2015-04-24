<?php
namespace Multiple\Models;

class UserRole extends BaseModel{
	public function getSource(){
		return $this->tb_prefix."user_role";
	}
	
}