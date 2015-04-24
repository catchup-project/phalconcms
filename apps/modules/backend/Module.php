<?php

namespace Multiple\Backend;

class Module
{

	public function registerAutoloaders()
	{

		$loader = new \Phalcon\Loader();

		$loader->registerNamespaces(array(
			'Multiple\Backend\Controllers' => BACKEND_CONTROLLERS_DIR,
		));

		$loader->register();
	}

	/**
	 * Register the services here to make them module-specific
	 */
	public function registerServices($di)
	{

		//Registering a dispatcher
		$di->set('dispatcher', function() {
			$dispatcher = new \Phalcon\Mvc\Dispatcher();
			$dispatcher->setDefaultNamespace("Multiple\Backend\Controllers\\");
			return $dispatcher;
		});

        /**
         * Setting up the view component
         */
        //Registering a shared view component
        $di->set('view', function() {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir(BACKEND_VIEWS_DIR);
            $view->registerEngines(array(".phtml" => 'volt'));
            return $view;
        });

        /**
         * Setting up volt
         */
        $di->set('volt', function($view, $di) {
            $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
            $volt->setOptions(
            		array(
                		"compiledPath" => BACKEND_CACHE_DIR,
				'compiledSeparator' => '_',
            			'compileAlways'=>true,
              			 "compiledExtension" => ".php"
            ));
            return $volt;
        }, false);

	}

}