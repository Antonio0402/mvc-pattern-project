<?php

declare(strict_types=1);

namespace App\Framework;

use ReflectionMethod;
use App\Framework\Container;
use App\Framework\Exceptions\PageNotFoundException;
use App\Framework\Request;
use App\Framework\TemplateViewerInterface;

class Dispatcher
{
    public function __construct(
        private Routes $route,
        private Container $container,
    ) {
    }

    public function dispatch(Request $request): void
    {
        $path = $this->getPath($request->uri);
        $request_method = $request->method;

        $route = $this->route->match($path, $request_method);

        if ($route === false) {
            // http_response_code(404);
            // exit('404 Not Found');
            throw new PageNotFoundException("404 Not Found on route of {$path}");
        }

        $action = $this->getActionName($route['action']) ?? 'index';
        $controller = $this->getControllerName($route['controller'], $route['namespace']) ?? 'home';

        // require_once CONTROLLERS_PATH . $controller . '.php';
        // $controller_class = 'App\Controllers\\' . ucfirst($controller);

        $controller_object = $this->container->get($controller);

        $controller_object->setRequest($request);

        $controller_object->setView($this->container->get(TemplateViewerInterface::class));

        $args = $this->getArgumentsFromRoute($controller, $action, $route);

        $controller_object->$action(...$args);

    }



    public function getArgumentsFromRoute(string $controller, string $action, array $route)
    {
        $method = new ReflectionMethod($controller, $action);
        $parameters = $method->getParameters();
        $args = [];
        foreach ($parameters as $param) {
            $name = $param->getName();
            if ($name) {
                $args[$name] = !$param->isOptional() ? $route[$name] : null;
            }
        }
        return $args;
    }

    public function getControllerName(string $controller, string $namespace = ''): string
    {
        $name = strtolower($controller);
        $name = str_replace('-', '', ucwords($name, '-'));
        $controllerNamespace = "App\Controllers";
        if ($namespace) {
            $controllerNamespace = '\\' . $namespace;
        }
        return $controllerNamespace . "\\" . $name;
    }

    public function getActionName(string $action): string
    {

        $name = strtolower($action);
        $name = lcfirst(str_replace('-', '', ucwords($name, '-')));
        return $name;
    }
    private function getPath(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH);

        if ($path === false) {

            throw new UnexpectedValueException("Malformed URL: '$uri'");

        }

        return $path;
    }
}
