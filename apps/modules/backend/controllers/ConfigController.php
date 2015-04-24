<?php

namespace Multiple\Backend\Controllers;
use Multiple\Components\Base,
	Multiple\Models\ConfigGroup,
	Multiple\Models\Config;

/**
 * Class ConfigsController
 * @package Multiple\Backend\Controllers
 * 配置文件 设置；
 */


class ConfigController extends Base
{	
	
	public function indexAction(){
		 $config=new Config();
		 $configs=$config->getConfigs();
		 $this->view->setVar("configs", $configs);
	}
	
	public function addAction(){
		$request=$this->request;
		if($request->isPost()){
			$config=new Config();
			$res=$config->saveField($_POST);
			$result=array('status'=>0,'info'=>'添加失败');
			if($res===true){
				$result=array('status'=>1,'info'=>'添加成功','location'=>'/admin/config/index');
			}
			return $this->sendJson($result);
		}
		$groups=ConfigGroup::find()->toArray();	
		$this->view->setVar("groups", $groups);
	}
    public function memcachedAction(){
        $memcache = "[memcache]".PHP_EOL
            ."hostname     = 127.0.0.1".PHP_EOL
            ."port         = 11211".PHP_EOL
            ."weight       = 1".PHP_EOL
            ."lifetime     = 3600".PHP_EOL
            ."persistent   = TRUE".PHP_EOL
            ."prefix       = my_".PHP_EOL;
        file_put_contents(CONFIG_DIR.'memcached.ini', $memcache);

        echo "success";die();
    }

    public function redisAction(){
        $redis = "[redis]".PHP_EOL
            ."host         = 127.0.0.1".PHP_EOL
            ."port         = 6379".PHP_EOL
            ."database     = 1".PHP_EOL
            ."lifetime     = 3600".PHP_EOL;
        file_put_contents(CONFIG_DIR.'redis.ini', $redis);
        echo "success";
        die();
    }

    public function testAction(){
        $memcache = "<?php".PHP_EOL
            ."if (!defined('BASE_PATH')) exit('No direct script access allowed');".PHP_EOL.PHP_EOL
            ."\$config = array(".PHP_EOL
            ."	'default' => array(".PHP_EOL
            ."		'hostname' => '127.0.0.1',".PHP_EOL
            ."		'port'     => '11211',".PHP_EOL
            ."		'weight'   => '1',".PHP_EOL
            ."	),".PHP_EOL
            .");".PHP_EOL;
        file_put_contents(CONFIG_DIR.'memcached.php', $memcache);

        $this->config->load('memcached', TRUE);
        $memcached = $this->config->item('default');

        $this->config->set_item('index_page', 'settings.php');
        var_dump( $this->config->item('index_page'));

        var_dump( $memcached);die();
        echo "success";
        die();
    }
    
    
   

}