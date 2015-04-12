<?php

require_once dirname(__FILE__) . '/vendor/google-api-php-client/src/Google/autoload.php';

function php_ews_autoload($class_name)
{
    $base_path = 'php-ews';
    if ($class_name == 'EWS_Exception') {
        $include_file = $base_path . '/' . $class_name . '.php';
    } else {
        $include_file = $base_path . '/' . str_replace('_', '/', $class_name) . '.php';
    }

    $include_file = dirname(__FILE__) . '/vendor/' . $include_file;
    return (file_exists($include_file)) ? require_once $include_file : false;
}
spl_autoload_register('php_ews_autoload');

function eggsink_autoload($class_name)
{
    $include_file = dirname(__FILE__) . '/src/' . $class_name . '.php';
    return (file_exists($include_file)) ? require_once $include_file : false;
}
spl_autoload_register('eggsink_autoload');