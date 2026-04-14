<?php
    $entityManager = require __DIR__.'/../src/Infrastructure/Doctrine/bootstrap.php';

    use App\Infrastructure\Http\Routing\RouteCollection;

    $routes = new RouteCollection(__DIR__.'/../config/routes.php');
    $app = new App\Infrastructure\Http\Routing\Router($routes);