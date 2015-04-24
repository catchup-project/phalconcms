<?php

/**
 * Register application modules
 */
//Register the installed modules
$application->registerModules(array(
    'frontend' => array(
        'className' => 'Multiple\Frontend\Module',
        'path' => BASE_PATH.'/apps/modules/frontend/Module.php'
    ),
    'backend' => array(
        'className' => 'Multiple\Backend\Module',
        'path' => BASE_PATH.'/apps/modules/backend/Module.php'
    )
));
