<?php
namespace Multiple\Models;

class UserNode extends BaseModel{
	public function getSource(){
		return $this->tb_prefix."user_node";
	}
	
}