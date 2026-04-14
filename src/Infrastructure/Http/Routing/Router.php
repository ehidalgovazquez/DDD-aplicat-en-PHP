<?php
    namespace App\Infrastructure\Http\Routing;

    use App\Infrastructure\Http\RequestAPI;
    use App\Infrastructure\Http\ResponseJson;
    use Doctrine\ORM\EntityManagerInterface;
    use Dom\Entity;

    class Router {
        private RouteCollection $routeCollection;
        
        public function __construct(RouteCollection $routeCollection) {
            $this->routeCollection = $routeCollection;
        }

        public function dispatch(RequestAPI $request, EntityManagerInterface $em) {
            $routes = $this->routeCollection->getRoutes();
            $found = false;

            foreach($routes as $route){
                if($route['method'] === strtoupper($request->getMethod()) && $this->matchUri($route['path'], $request->getUri(), $params)) {
                    $found = true;
                    [$controllerClass, $action] = $route['handler'];
                    $controller = new $controllerClass($request, $em);
                    $actionArgs = array_merge([$request], array_values($params));
                    call_user_func_array([$controller, $action], $actionArgs);
                }
            }

            if(!$found) {
                $response = new ResponseJson(404, 'Not Found');
                $response->send();
            }
        }

        private function matchUri(string $routePath, string $requestUri, &$params): bool {
            $pattern = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';
            if(preg_match($pattern, $requestUri, $matches)) {
                $params = array_filter($matches, fn($key) => is_string($key), ARRAY_FILTER_USE_KEY);
                return true;
            }
            return false;
        }
    }