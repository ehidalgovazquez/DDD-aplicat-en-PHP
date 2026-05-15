<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$paths = [__DIR__ . '/../src/Domain'];
$isDevMode = true;

$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

$driver = $_ENV['DB_DRIVER'] ?? 'pdo_mysql';

if ($driver === 'pdo_sqlite') {
    $connectionParams = [
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/../' . ($_ENV['DB_PATH'] ?? 'database.sqlite'),
    ];
} else {
    $connectionParams = [
        'dbname'   => $_ENV['DB_NAME'] ?? 'school_management',
        'user'     => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASS'] ?? '',
        'host'     => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'driver'   => $driver,
    ];
}

$connection = DriverManager::getConnection($connectionParams, $config);

return new EntityManager($connection, $config);