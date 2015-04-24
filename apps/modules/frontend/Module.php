<?php

namespace Multiple\Frontend;

class Module
{

	public function registerAutoloaders()
	{

		$loader = new \Phalcon\Loader();

		$loader->registerNamespaces(array(
			'Multiple\Frontend\Controllers' => BASE_PATH.'/apps/modules/frontend/controllers/',
		));

		$loader->register();
	}

	/**
	 * Register the services here to make them module-specific
	 */
	public function registerServices($di)
	{

		//Registering a dispatcher
		$di->set('dispatcher', function () {
			$dispatcher = new \Phalcon\Mvc\Dispatcher();
			$dispatcher->setDefaultNamespace("Multiple\Frontend\Controllers\\");
			return $dispatcher;
		});

        /**
         * Setting up the view component
         */
        //Registering a shared view component
        $di->set('view', function() {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir(FRONTEND_VIEWS_DIR);
            $view->registerEngines(array(".phtml" => 'volt'));
            return $view;
        });

        /**
         * Setting up volt
         */
        $di->set('volt', function($view, $di) {
            $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
            $volt->setOptions(array(
                "compiledPath" => FRONTEND_CACHE_DIR,
                'compiledSeparator' => '_',
                "compiledExtension" => ".html"
            ));

            //自定义过滤器
            $compiler = $volt->getCompiler();
            $compiler->addFilter('int', function($resolvedArgs, $exprArgs) {
                return 'intval(' . $resolvedArgs . ')';
            });

            $compiler->addFilter('concat', function($resolvedArgs, $exprArgs) {
                return 'trim('.$resolvedArgs.')';
            });

            return $volt;
        }, true);

	}

}