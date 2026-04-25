<?php
    namespace App\Infrastructure\Http\Routing;

    use Exception;

    class RouteCollection {
        private array $routes = [];

        public function __construct(string $filePath) {
            $this->loadFromFile($filePath);
        }

        public function add(string $method, string $path, callable|array $handler) {
            $this->routes[] = [
                'method' => strtoupper($method),
                'path' => $path,
                'handler' => $handler
            ];
        }

        public function getRoutes(): array {
            return $this->routes;
        }

        private function loadFromFile(string $filePath) {
            if(!file_exists($filePath)) {
                throw new Exception("Routes file not found: $filePath");
            }
            
            $routes = require $filePath;

            if(!is_array($routes)) {
                throw new Exception("Routes file must return an array");
            }

            foreach($routes as $route) {
                if(!isset($route['method'], $route['path'], $route['handler'])) {
                    throw new Exception("Each route must have 'method', 'path', and 'handler' keys");
                }

                $this->add($route['method'], $route['path'], $route['handler']);
            }
        }
    }