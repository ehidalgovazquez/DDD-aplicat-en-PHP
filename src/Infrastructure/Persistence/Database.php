<?php

namespace App\Infrastructure\Persistence;

use PDO;

final class Database {
    public static function getConnection(): PDO {
        $path = __DIR__ . '/../../../database.sqlite';
        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}