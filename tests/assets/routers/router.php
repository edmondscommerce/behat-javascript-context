<?php

require_once __DIR__ . '/../../../vendor/autoload.php';
ini_set('display_errors', 1);

$router = new EdmondsCommerce\MockServer\StaticRouter();
$router->addStaticRoute('/', __DIR__.'/html/test.html');
$router->addStaticRoute('/alert', __DIR__.'/html/alert.html');
$router->addStaticRoute('/jquery', __DIR__.'/html/jquery.html');

$router->addStaticRoute('/slow-loading-js-host', __DIR__.'/html/slowpage.html');
$router->addCallbackRoute('/slow-loading-js', '', function () {
    sleep(12);
    return file_get_contents(__DIR__ . '/html/documentWrite.js');
});

// JQUERY AJAX TESTING
$router->addCallbackRoute('/jquery-ajax', '', function () {
    $search = ['IP_ADDRESS'];
    $replace = [getContainerIp()];
    $subject = file_get_contents(__DIR__.'/html/jquery-ajax.html');

    return str_replace($search, $replace, $subject);
});
$router->addCallbackRoute('/jquery-ajax-slow', '', function () {
    $search = ['IP_ADDRESS'];
    $replace = [getContainerIp()];
    $subject = file_get_contents(__DIR__.'/html/jquery-ajax-slow.html');

    return str_replace($search, $replace, $subject);
});
$router->addRoute('/jquery-ajax-response', 'AJAX TEXT');
$router->addCallbackRoute('/jquery-ajax-slow-response', '', function () {
    sleep(12);
    return 'AJAX TEXT';
});
// JQUERY AJAX TESTING

// PROTOTYPE.JS AJAX TESTING
$router->addCallbackRoute('/prototypejs-ajax', '', function () {
    $search = ['IP_ADDRESS'];
    $replace = [getContainerIp()];
    $subject = file_get_contents(__DIR__.'/html/prototypejs-ajax.html');

    return str_replace($search, $replace, $subject);
});
$router->addCallbackRoute('/prototypejs-ajax-slow', '', function () {
    $search = ['IP_ADDRESS'];
    $replace = [getContainerIp()];
    $subject = file_get_contents(__DIR__.'/html/prototypejs-ajax-slow.html');

    return str_replace($search, $replace, $subject);
});
$router->addRoute('/prototypejs-ajax-response', 'AJAX TEXT');
$router->addCallbackRoute('/prototypejs-ajax-slow-response', '', function () {
    sleep(12);
    return 'AJAX TEXT';
});
// PROTOTYPE.JS AJAX TESTING

// JQUERY AND PROTOTYPE.JS AJAX TESTING
$router->addCallbackRoute('/jquery-and-prototypejs-ajax', '', function () {
    $search = ['IP_ADDRESS'];
    $replace = [getContainerIp()];
    $subject = file_get_contents(__DIR__.'/html/jquery-and-prototypejs-ajax.html');

    return str_replace($search, $replace, $subject);
});
$router->addCallbackRoute('/jquery-and-prototypejs-ajax-slow', '', function () {
    $search = ['IP_ADDRESS'];
    $replace = [getContainerIp()];
    $subject = file_get_contents(__DIR__.'/html/jquery-and-prototypejs-ajax-slow.html');

    return str_replace($search, $replace, $subject);
});
// JQUERY AND PROTOTYPE.JS AJAX TESTING


$router->addRoute('/admin', 'Admin Login');
$router->setNotFound('Not found');
$router->run()->send();
