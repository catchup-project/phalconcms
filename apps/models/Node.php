<?php
namespace Multiple\Models;
class Node extends  BaseModel{
 	public function getSource(){
        	return $this->tb_prefix."node";
    }
    
    /**
     * 得到有层次关系的所有节点
     * @return array
     */
    public static function getAllNode(){
    	$parent=self::find("level=1")->toArray();
    	$arr=array();
    	foreach($parent as $item){
    		$item['child']=self::find("parent_id=".$item['id'])->toArray();
    		$arr[]=$item;
    	}
    	return $arr;
    }
}