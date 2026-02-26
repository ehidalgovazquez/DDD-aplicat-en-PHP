<?php

namespace App\Infrastructure\Http;

final class Request
{
    private array $params;

    public function __construct() {
        $this->params = array_merge($_GET, $_POST);
    }

    public function get(string $key, $default = null) {
        return $this->params[$key] ?? $default;
    }

    public function all(): array {
        return $this->params;
    }
}