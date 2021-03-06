<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Sessions
 */
session_start();


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('{controller}/{id:(\d+)\-(\d+)\-(\d+)}/{action}');
$router->add('{controller}/{action}/{id:(\d+)\-(\d+)\-(\d+)}');
$router->add('{controller}/{action}/{id:\d+}');

$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('members', ['controller' => 'Members', 'action' => 'index']);
$router->add('controlpanel', ['controller' => 'controlpanel', 'action' => 'index']);
$router->add('terms', ['controller' => 'Terms', 'action' => 'index']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('signup/activate/{token:[\da-f]+}', ['controller' => 'Signup', 'action' => 'activate']);

$router->add('{controller}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
$router->add('admin/{controller}/{action}/{id:\d+}', ['namespace' => 'Admin']);
$router->dispatch($_SERVER['QUERY_STRING']);

/*
echo "<pre>";
print_r($router);
echo "</pre>";
*/