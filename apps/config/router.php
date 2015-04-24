<?php

/**
 * 设置路由
 */

//Registering a router
$di->set('router', function(){

    $router = new \Phalcon\Mvc\Router();

    /**
     * 前台路由控制
     */
    $router->setDefaultModule("frontend");

    //设置默认登录
    $router->add('/', array(
        'module' => 'frontend',
        'namespace'=>'Multiple\Frontend\Controllers\\',
        'controller' => 'login',
        'action' => 'index'
    ));

    $router->add("/login/:action", array(
        'module' => 'Frontend',
        'namespace'=>'Multiple\Frontend\Controllers\\',
        'controller' => 'login',
        'action' => 'index',
    ));

    $router->add("/saas/:action/:params", array(
        'module' => 'Frontend',
        'namespace'=>'Multiple\Frontend\Controllers\\',
        'controller' => 'saas',
        'action' => 1,
        'params' => 2,
    ));


    $router->add("/user/:action/:params", array(
        'module' => 'Frontend',
        'namespace'=>'Multiple\Frontend\Controllers\\',
        'controller' => 'user',
        'action' => 1,
        'params' => 2,
    ));

    $router->add('/forestry/:action/:params', array(
        'module' => 'frontend',
        'namespace'=>'Multiple\Frontend\Controllers\\',
        'controller' => 'forestry',
        'action' => 1,
        'params' => 2,
    ));


    $router->add('/:controller/:action/:params', array(
        'module' => 'frontend',
        'namespace'=>'Multiple\Frontend\Controllers\\',
        'controller' => 1,
        'action' => 2,
        'params' => 3,
    ));


    $router->add('/artist/{id:[0-9]+}/{name}', array(
        'module' => 'frontend',
        'namespace' => 'Multiple\Frontend\Controllers\\',
        'controller' => 'catalog',
        'action' => 'artist'
    ));

    $router->add('/album/{id:[0-9]+}/{name}', array(
        'module' => 'frontend',
        'namespace' => 'Multiple\Frontend\Controllers\\',
        'controller' => 'catalog',
        'action' => 'album',
    ));

    $router->add('/tag/{name}', array(
        'module' => 'frontend',
        'namespace' => 'Multiple\Frontend\Controllers\\',
        'controller' => 'catalog',
        'action' => 'tag'
    ));

    $router->add('/tag/{name}/{page:[0-9]+}', array(
        'module' => 'frontend',
        'namespace' => 'Multiple\Frontend\Controllers\\',
        'controller' => 'catalog',
        'action' => 'tag'
    ));

    $router->add('/search(/?)', array(
        'module' => 'frontend',
        'namespace' => 'Multiple\Frontend\Controllers\\',
        'controller' => 'catalog'
    ,	'action' => 'search'
    ));

    $router->add('/popular', array(
        'module' => 'frontend',
        'namespace' => 'Multiple\Frontend\Controllers\\',
        'controller' => 'catalog',
        'action' => 'popular'
    ));

    $router->add('/charts', array(
        'module' => 'frontend',
        'namespace' => 'Multiple\Frontend\Controllers\\',
        'controller' => 'catalog',
        'action' => 'charts'
    ));

    $router->add('/about', array(
        'module' => 'frontend',
        'namespace' => 'Multiple\Frontend\Controllers\\',
        'controller' => 'about',
        'action' => 'index'
    ));

    /**
     * 后台路由控制；
     */

    //后台首页
    $router->add("/admin/index/:action", array(
        'module' => 'backend',
        'namespace'=>'Multiple\Backend\Controllers\\',
        'controller' => 'index',
        'action' => 1,
    ));
  
	$router->add('/admin/:controller/:action/:params', array(
		'module'=>'backend',
		'controller' => 1,
		'action' => 2,
		'params' => 3,
	));

    $router->add('/admin/:controller[/]?', array(
    		'module' => 'backend',
    		'controller' => 1,
    		'action' => 'index',
    ));
    $router->add('/admin[/]?', array(
    		'module' => 'backend',
    		'controller' => 'login',
    		'action' => 'index',
    ));

    $router->add("/admin/login/:action/:params", array(
        'module' => 'backend',
        'namespace'=>'Multiple\Backend\Controllers\\',
        'controller' => 'login',
        'action' => 1,
        'params' => 2,
    ));

    /**
     * 404 页面
     */

    $router->notFound(array(
        'module'        => 'frontend',
        'namespace'     => 'Multiple\Frontend\Controllers\\',
        'controller'    => 'error',
        'action'        => 'show'
    ));

    return $router;
});