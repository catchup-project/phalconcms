<?php
namespace Multiple\Models;

class BaseModel extends \Phalcon\Mvc\Model
{
    public $tb_prefix;

    /**
     * 配置 多个数据库 一个是写的数据库，一个是读的数据库
     */
    public function initialize()
    {	
        $this->setWriteConnectionService('db');  //链接主数据库
        $this->setReadConnectionService('dbSlave');    //链接从数据库
       
    }
    public function onConstruct(){
    	 $this->tb_prefix = DATABASE_PREFIX;            //设置表的前缀
    }

    public function getSource()
    {	
        return $this->tb_prefix . strtolower(get_class($this));
    }
    /**
     * 添加
     * @param unknown $data
     * @return boolean
     */
    public function saveField($data=array()){
    	$fields= $this->getModelsMetaData()->getAttributes($this);
		$post=array();
		$model=new $this();
		foreach($data as $key=>$item){
			if(in_array($key, $fields)){
					$post[$key]=$item;
					$model->$key=$item;					
			}
		}
		if(!$model->save()){
			foreach($model->getMessages() as $message){
				print_r($message);exit;
			}		
		}else{
			return true;
		}
    }
   
}