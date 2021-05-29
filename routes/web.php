<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/api/v1/deploy/{env}/{type}[/{git_check}]', function ($env, $type, $git_check=null) use ($router) {
    $cmd = "";
    $PROJECT_PATH = $_ENV["PROJECT_PATH"];
    $SERVICE_NAME = $_ENV["SERVICE_NAME"];
    $DOCKER_COMPOSE_FILE_ABS_PATH = $_ENV["DOCKER_COMPOSE_FILE_ABS_PATH"];
    if ( $env == "staging" && $type == "canery" ) {
        $cmd=<<<EOF
        cd $PROJECT_PATH
        git pull gitlab master
        docker build -t $SERVICE_NAME:latest .
        docker build -t $SERVICE_NAME:$(git rev-parse --short HEAD) .
        docker-compose -f $DOCKER_COMPOSE_FILE_ABS_PATH down --rmi local --remove-orphans
        docker-compose -f $DOCKER_COMPOSE_FILE_ABS_PATH up --force-recreate
        EOF;
    }
    if ( $env == "production" && $type == "canery" ) {
        $cmd=<<<EOF
        cd $PROJECT_PATH
        git pull gitlab master
        docker build -t $SERVICE_NAME:latest .
        docker build -t $SERVICE_NAME:$(git rev-parse --short HEAD) .
        docker-compose -f $DOCKER_COMPOSE_FILE_ABS_PATH down --rmi local --remove-orphans
        docker-compose -f $DOCKER_COMPOSE_FILE_ABS_PATH up --force-recreate
        EOF;
    }
    $output = shell_exec($cmd);
    return response()->json([
        'cmd' => $output,
        'env' => $env,
        'type' => $type,
        'commit' => $git_check
    ], 201);
});