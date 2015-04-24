<?php

namespace Multiple\Models;



class Config extends BaseModel
{
	
	public function initialize(){
		$this->belongsTo('group_id','ConfigGroup','id');
	}
	
    public function getSource()
    {	
        return $this->tb_prefix."config";
    }
   

	public function getConfigs(){
		$configGroups=ConfigGroup::find()->toArray();
		if(count($configGroups)>0){
			foreach($configGroups as &$group){
				 $group['children']=Config::find("group_id=".$group['id'])->toArray();
			}
		}
		return $configGroups;
	}
}
