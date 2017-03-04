<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

/***************************/
// Register service providers
/***************************/

// Doctrine DB
$app->register(new Silex\Provider\DoctrineServiceProvider());
// Twig template
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
// Asset
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1'
));
// Session
$app->register(new Silex\Provider\SessionServiceProvider());
// Security
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users' => function () use ($app) {
                return new MicroImmo\DAO\UserDAO($app['db']);
            },
        ),
    ),
));
// Register DAO services
$app['dao.annonce'] = function ($app) {
    return new MicroImmo\DAO\AnnonceDAO($app['db']);
};
$app['dao.user'] = function ($app) {
    return new MicroImmo\DAO\UserDAO($app['db']);
};
