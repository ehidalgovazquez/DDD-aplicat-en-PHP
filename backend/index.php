<?php
    require __DIR__ . '/vendor/autoload.php';

    use Dotenv\Dotenv;
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    require __DIR__.'/public/bootstrap.php';

    use App\Infrastructure\Http\RequestAPI;

    $req = new RequestAPI();
    $app->dispatch($req, $entityManager);