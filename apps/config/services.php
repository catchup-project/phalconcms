<?php
use Multiple\Library\Acl;
use Multiple\Library\Auth;

$di->set('config', function () use ($config) {
    return $config;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function() use ($config) {
    $url = new \Phalcon\Mvc\Url();
    $url->setBaseUri($config->application->baseUri);
    //$url->setBaseUri('http://phalcon.mydomain.com'); //设置全路径
    return $url;
});


/**
 * Database connection is created based in the parameters defined in the configuration file
 */

$di->set('profiler', function(){
    return new Phalcon\Db\Profiler();
}, true);

/**
 * 主数据库
 */

$di->set('db', function() use ($config) {
    $eventsManager = new Phalcon\Events\Manager();

    $logger = new Phalcon\Logger\Adapter\File(BASE_PATH.$config->application->logsDir."sql_".date("Y_m_d",time()).".log");

    $eventsManager->attach('db', function($event, $connection) use ($logger) {
        if ($event->getType() == 'beforeQuery') {
            $logger->log($connection->getSQLStatement(), Phalcon\Logger::INFO);
        }

        if ($event->getType() == 'afterQuery') {}

    });

    $connection = new Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host" => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname" => $config->database->name,
        "prefix" => $config->database->prefix,
        "options" => array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING ,PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
    ));

    $connection->setEventsManager($eventsManager);

    return $connection;
});


/**
 * 从数据库
 */
$di->set('dbSlave',function() use ($config) {
    $eventsManager = new Phalcon\Events\Manager();

    $logger = new Phalcon\Logger\Adapter\File(BASE_PATH.$config->application->logsDir."sql_".date("Y_m_d",time()).".log");

    $eventsManager->attach('db', function($event, $connection) use ($logger) {
        if ($event->getType() == 'beforeQuery') {
            $logger->log($connection->getSQLStatement(), Phalcon\Logger::INFO);
        }

        if ($event->getType() == 'afterQuery') {}

    });


    $connection = new Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host" => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname" => $config->database->name,
        "prefix" => $config->database->prefix,
        "options" => array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING ,PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
    ));

    $connection->setEventsManager($eventsManager);

    return $connection;
});


/**
 * Register a user component
 */
$di->set('elements', function(){
    return new Elements();
});


$di->set('captcha', function(){
    return new Captcha();
});

//Captcha

/**
 * Register the flash service with custom CSS classes
 */
$di->set('flash', function(){
    $flash = new Phalcon\Flash\Direct(array(
        'error' => 'alert alert-error',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
    ));
    $flash->setImplicitFlush(false);
    return $flash;
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function() use ($config){

    //session 保存到文件中；
    
    $session = new Phalcon\Session\Adapter\Files();
    $session->start();
    

    //session 用memcached保存到内存中；
   /* $session = new Phalcon\Session\Adapter\Memcache(array(
        'host' => $config->memcache->host,
        'port' => $config->memcache->port,
        'lifetime' => $config->memcache->lifetime,
        'persistent' => $config->memcache->persistent,
        'prefix' => $config->memcache->prefix
    ));

    $session->start();*/
    return $session;
});


$di->set('memcache', function() use ($memcached_config){

    // Cache data for 1
    $frontCache = new Phalcon\Cache\Frontend\Data(array(
        "lifetime" => $memcached_config->memcache->lifetime
    ));

    $cacheConnect = new Phalcon\Cache\Backend\Memcache($frontCache, array(
        'host' => $memcached_config->memcache->host,
        'port' => $memcached_config->memcache->port,
        "lifetime" => $memcached_config->memcache->lifetime,
        'persistent' => $memcached_config->memcache->persistent,
        'prefix' => $memcached_config->memcache->prefix
    ));

    return $cacheConnect;
});


$di->set("redis", function() use ($redis_config) {
    $redisConnect = new Redis();
    $redisConnect->connect($redis_config->redis->host, $redis_config->redis->port);
    $redisConnect->select($redis_config->redis->database);
    return $redisConnect;
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function() use ($config) {
    if (isset($config->models->metadata)) {
        $metaDataConfig = $config->models->metadata;
        $metadataAdapter = 'Phalcon\Mvc\Model\Metadata\\'.$metaDataConfig->adapter;
        return new $metadataAdapter();
    }
    return new Phalcon\Mvc\Model\Metadata\Memory();
});

$di->set('cookies', function() {
    $cookies = new Phalcon\Http\Response\Cookies();
    $cookies->useEncryption(true);
    return $cookies;
});

$di->set('crypt', function() {
    $crypt = new Phalcon\Crypt();
    $crypt->setKey('#1dj8$=dp?.ak//j1V$'); //Use your own key!
    return $crypt;
});


$di->set('modelsManager', function(){
    return new Phalcon\Mvc\Model\Manager();
});

$di->set('config', function() {
    return new Config();
});


$di->set('email', function() {
    return new Email();
});


$di->set('xmlrpc', function() {
    return new Xmlrpc();
});

$di->set('xmlrpcs', function() {
    return new Xmlrpcs();
});

$di->set('auth', function () {
    return new Auth();
});

$di->set('acl', function() {
    return  new Acl();
});


