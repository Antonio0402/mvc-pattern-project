<?php

// require_once APP_PATH . 'Routes.php';

$routes = new App\Framework\Routes();
$route->get("admin/{controller}/{action}", ["namespace" => 'Admin']);
$route->get("{title}/{id:\d+}/{page:\w+}", ["controller" => "transactions", "action" => "showPage"]);
$router->get("/transactions/{slug:[\w-]+}", ["controller" => "transactions", "action" => "show"]);

// $router->get("/{controller}/{id:\d+}/{action}");

$router->get("/{controller}/{id:\d+}/show", ["action" => "show"]);
$router->get("/{controller}/{id:\d+}/edit", ["action" => "edit"]);
$router->get("/{controller}/{id:\d+}/update", ["action" => "update"]);
$router->get("/{controller}/{id:\d+}/delete", ["action" => "delete"]);
$router->post("/{controller}/{id:\d+}/destroy", ["action" => "destroy"]);



$routes->get('transactions/index', [ 'controller' => 'transactions', 'action' => 'index' ]);
$routes->get('/', [ 'controller' => 'home', 'action' => 'index' ]);
$routes->get('/{controller}/{action}');

return $routes;
