<?php

use App\Middleware\TokenValidation;
use App\Controllers\AuthController;
use App\Controllers\UsersController;
use App\Controllers\RedcapController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function(App $app) {
  $tokenValidation = $app->getContainer()->get(TokenValidation::class);

  $app->get('/', \App\Actions\HomeAction::class);

  $app->group('/api/auth', function (RouteCollectorProxy $group) {

    $group->post('/signup', AuthController::class . ':postSignup');

    $group->post('/login', AuthController::class . ':postLogin');
  });

  $app->group('/api', function (RouteCollectorProxy $group) {

    $group->group('/auth', function (RouteCollectorProxy $group) {

      $group->post('/auth', AuthController::class . ':postAuth');

      $group->post('/logout', AuthController::class . ':postLogout');
    });

    $group->group('/users', function (RouteCollectorProxy $group) {

      $group->get('/{slug}', UsersController::class . ':getUser');

      $group->post('/update/{slug}', UsersController::class . ':postUpdate');
    });

    $group->group('/redcap', function (RouteCollectorProxy $group) {

      $group->get('', RedcapController::class . ':getReports');
    });

  })->add($tokenValidation);
};
