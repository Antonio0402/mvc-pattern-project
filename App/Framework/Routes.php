<?php

declare(strict_types=1);

namespace App\Framework;

class Routes
{
    private $routes = [];
    public function add(string $requestMethod, string $path, array $params): self
    {
        $this->routes[$requestMethod] = [
            'path' => $path,
            'params' => $params
        ];

        return $this;

    }

    public function get(string $path, array $params): self
    {
        return $this->add('get', $path, $params);
    }

    public function post(string $path, array $params): self
    {
        return $this->add('post', $path, $params);
    }

    public function routes()
    {
        return $this->routes;
    }

    public function match(string $path, string $request_method): array|bool
    {

        $path = urldecode($path);

        $path = trim($path, '/');

        // $pattern = "#^/(?<controller>[a-z]+)/(?<action>[a-z]+)$#";
        // if (preg_match($pattern, $path, $matches)) {
        //     $matches = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        //     return $matches;
        // }
        foreach ($this->routes[$request_method] as $route) {
            $pattern = $this->getPatternFromRoutePath($route['path']);

            if (preg_match($pattern, $path, $matches)) {

                $matches = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                $params = array_merge($matches, $route["params"]);


                // if (array_key_exists("method", $params)) {

                //     if (strtolower($request_method) !== strtolower($params["method"])) {

                //         continue;

                //     }

                // }


                return $params;

            }

        }
        return false;
    }

    private function getPatternFromRoutePath(string $route_path): string
    {
        $route_path = trim($route_path, '/');
        $segments = explode('/', $route_path);
        $segments = array_map(function (string $segment) {
            if (preg_match("#^\{([a-z0-9A-Z]*)\}$#", $segment, $matches)) {
                return "(?<" . $matches[1] . ">)";
            }

            if (preg_match("#^\{([a-z0-9A-Z]*):(.+)\}$#", $segments, $matches)) {
                return "(?<" . $matches[1] . ">" . $matches[2] . ")";
            }
            return $segment;
        }, $segments);

        return "#^" . implode("/", $segments) . "$#iu";

    }
}
