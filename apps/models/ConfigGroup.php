<?php
namespace Multiple\Models;
class ConfigGroup extends BaseModel{
	public function getSource()
	{
		return $this->tb_prefix."config_group";
	}
	
	public function beforeValidationOnCreate()
	{
		$this->isshow =1;
		$this->system=0;
	}
}