<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
ini_set('display_errors', 1);


$router = new EdmondsCommerce\MockServer\StaticRouter();
$router->addStaticRoute('/', __DIR__.'/html/test.html');
$router->addStaticRoute('/alert', __DIR__.'/html/alert.html');
$router->addStaticRoute('/jquery', __DIR__.'/html/jquery.html');


$router->addStaticRoute('/ajax', __DIR__.'/html/ajax.html');
$router->addStaticRoute('/ajax-slow', __DIR__.'/html/ajax-slow.html');
$router->addRoute('/ajax-response', 'AJAX TEXT');
$router->addCallbackRoute('/ajax-slow-response', '', function() {
    sleep(12);
    return 'AJAX TEXT';
});


$router->addStaticRoute('/slow-loading-js-host', __DIR__.'/html/slowpage.html');
$router->addCallbackRoute('/slow-loading-js', '', function() {
   sleep(12);
   return file_get_contents(__DIR__ . '/html/documentWrite.js');
});

// JQUERY AJAX TESTING
$router->addStaticRoute('/jquery-ajax', __DIR__.'/html/jquery-ajax.html');
$router->addStaticRoute('/jquery-ajax-slow', __DIR__.'/html/jquery-ajax-slow.html');
$router->addRoute('/jquery-ajax-response', 'AJAX TEXT');
$router->addCallbackRoute('/jquery-ajax-slow-response', '', function() {
    sleep(12);
    return 'AJAX TEXT';
});
// JQUERY AJAX TESTING


$router->addRoute('/admin', 'Admin Login');
$router->setNotFound('Not found');
$router->run()->send();
