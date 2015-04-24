<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
	define('DEBUG',true);
    define('BASE_PATH',dirname(__DIR__));  //定义根目录；
    define('PUBLIC_ROOT' , __DIR__);  //定义public目录；
    define('APP_DIR', BASE_PATH . DIRECTORY_SEPARATOR.'apps'); //定义apps 目录

    $di = new \Phalcon\DI\FactoryDefault();

    $config = new Phalcon\Config\Adapter\Ini(BASE_PATH . DIRECTORY_SEPARATOR.'apps'. DIRECTORY_SEPARATOR.'config'. DIRECTORY_SEPARATOR.'config.ini');
    define('CONFIG_DIR' , BASE_PATH . $config->application->configDir);

    $memcached_config = new Phalcon\Config\Adapter\Ini(CONFIG_DIR . 'memcached.ini');
    $redis_config = new Phalcon\Config\Adapter\Ini(CONFIG_DIR . 'redis.ini');

    define('FRONTEND_CACHE_DIR', BASE_PATH.$config->application->frontendCacheDir);
    define('FRONTEND_VIEWS_DIR', BASE_PATH.$config->application->frontendViewsDir);//frontendViewsDir
    define('FRONTEND_CONTROLLERS_DIR', BASE_PATH.$config->application->frontendControllersDir);

    define('MODELS_DIR', BASE_PATH.$config->application->modelsDir);

    define('BACKEND_CACHE_DIR', BASE_PATH.$config->application->backendCacheDir);//backendCacheDir
    define('BACKEND_VIEWS_DIR', BASE_PATH.$config->application->backendViewsDir);
    define('BACKEND_CONTROLLERS_DIR', BASE_PATH.$config->application->backendControllersDir);

    define('LIBRARY_DIR', BASE_PATH.$config->application->libraryDir);
    define('PLUGINS_DIR', BASE_PATH.$config->application->pluginsDir);
    define('FORMS_DIR', BASE_PATH.$config->application->formsDir);     //定义语表单文件路径

    define('COMPONENTS_DIR', BASE_PATH.$config->application->componentsDir);

    define('LANGUAGE_DIR', BASE_PATH . $config->application->languageDir);  //定义语言文件路径

    define('DEFAULT_LANGUAGE', BASE_PATH . $config->language->default);  //定义语言文件路径


    define('DATABASE_PREFIX', $config->database->prefix);  //数据库
    define('DATABASE_NAME', $config->database->name);  //数据库

    define('DATABASE_BACKUP', BASE_PATH . $config->backup->default);  //数据库备份目录


    /**
     * 版本号
     * 微信公众平台相关
     */
    $wechat_config = new Phalcon\Config\Adapter\Ini(BASE_PATH . DIRECTORY_SEPARATOR.'apps'. DIRECTORY_SEPARATOR.'config'. DIRECTORY_SEPARATOR.'wechat.ini');
    define('LANEWECHAT_VERSION', $wechat_config->default->version);
    define('LANEWECHAT_VERSION_DATE', $wechat_config->default->version_date);
    define('WECHAT_TOKEN', $wechat_config->default->token);
    define("WECHAT_APPID", $wechat_config->default->app_id);
    define("WECHAT_APPSECRET", $wechat_config->default->app_secret);
    define("WECHAT_URL", $wechat_config->default->url);


    /**
     * 配置自动加载；
     */
    include BASE_PATH . DIRECTORY_SEPARATOR."apps". DIRECTORY_SEPARATOR."config". DIRECTORY_SEPARATOR."loader.php";

    /**
     * 配置路由；
     */
    include BASE_PATH . DIRECTORY_SEPARATOR."apps". DIRECTORY_SEPARATOR."config". DIRECTORY_SEPARATOR."router.php";

    /**
     * 配置服务；
     */
    include BASE_PATH . "/apps/config/services.php";

    $application = new \Phalcon\Mvc\Application();

    $application->setDI($di);

    /**
     * 配置多模块,
     */

    include BASE_PATH . DIRECTORY_SEPARATOR."apps". DIRECTORY_SEPARATOR."config". DIRECTORY_SEPARATOR."modules.php";

    echo $application->handle()->getContent();
}catch (Phalcon\Exception $e) {
    //把错误记录到错误日志中
    //$logger = new Phalcon\Logger\Adapter\File(BASE_PATH.$config->application->logsDir."error_".date("Y_m_d",time()).".log");
    //$logger->log(get_class($e).$e->getMessage().$e->getFile().$e->getLine().$e->getTraceAsString(), Phalcon\Logger::INFO);

    /**
     * Log the exception
     */
    $logger = new Phalcon\Logger\Adapter\File(BASE_PATH.$config->application->logsDir."error_".date("Y_m_d",time()).".log");
    $logger->error($e->getMessage());
    $logger->error('Code:'.$e->getCode());
    $logger->error('File:'.$e->getFile());
    $logger->error('Line:'.$e->getLine());

    /**
     * Show an static error page
     */
    //$response = new Phalcon\Http\Response();
    //$response->redirect('error/show');
    //$response->send();


    $response = new Phalcon\Http\Response();
    $response->setStatusCode(404, "Not Found");

    //Set the content of the response
    $response->setContent("Sorry,My Website  in maintenance.");

    //Send response to the client
    $response->send();

} catch (PDOException $e){
    //把错误记录到错误日志中
    if(DEBUG){
	    print_r(get_class($e).$e->getMessage().$e->getFile().$e->getLine().$e->getTraceAsString());
    }else{
	    $logger = new Phalcon\Logger\Adapter\File(BASE_PATH.$config->application->logsDir."error_pdo_".date("Y_m_d",time()).".log");
	    $logger->log(get_class($e).$e->getMessage().$e->getFile().$e->getLine().$e->getTraceAsString(), Phalcon\Logger::INFO);
	    $response = new Phalcon\Http\Response();
	    $response->setStatusCode(404, "Not Found");
	    //Set the content of the response
	    $response->setContent("Sorry,My Website  in maintenance.");
	   // Send response to the client
	   $response->send();
    }
}

