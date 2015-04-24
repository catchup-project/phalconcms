<?php
/*
 * 设置自动加载；
 */

$loader = new \Phalcon\Loader(); //自动加载

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        BASE_PATH . $config->application->pluginsDir,
        BASE_PATH . $config->application->libraryDir,
        BASE_PATH . $config->application->modelsDir,
        BASE_PATH . $config->application->formsDir,
        BASE_PATH . $config->application->componentsDir,
    )
)->register();

$loader->registerNamespaces(array(
    'Multiple\Models' => MODELS_DIR,
    'Multiple\Forms' => FORMS_DIR,
    'Multiple\Library' => LIBRARY_DIR,
    'Multiple\Plugins' => PLUGINS_DIR,
    'Multiple\Components' => COMPONENTS_DIR,
))->register();