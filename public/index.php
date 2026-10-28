<?php

declare(strict_types=1);

use App\App;
use App\Framework\Dispatcher;
use App\Framework\Request;

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;

define('APP_PATH', $root . 'App' . DIRECTORY_SEPARATOR);
define('MODELS_PATH', APP_PATH . 'Models' . DIRECTORY_SEPARATOR);
define('CONTROLLERS_PATH', APP_PATH . 'Controllers' . DIRECTORY_SEPARATOR);
define('DATA_PATH', MODELS_PATH . 'data' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', $root . 'config' . DIRECTORY_SEPARATOR);

require_once APP_PATH . 'App.php';
require_once APP_PATH . 'helpers.php';

function autoload($class)
{
    // Load from prefix and non-prefix
    $paths = [
        APP_PATH,
        dirname(__DIR__) . DIRECTORY_SEPARATOR
    ];

    foreach ($paths as $basePath) {
        $class = preg_replace('/^App\\\\/', '', $class);
        $file = $basePath . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

}

spl_autoload_register('autoload');

set_error_handler('App\Framework\ErrorHandler::handleError');

set_exception_handler('App\Framework\ErrorHandler::handleException');


$request = Request::getAllFromGlobals();

$route = require_once(CONFIG_PATH . 'routes.php');

$container = require_once(CONFIG_PATH . 'services.php');



// Prepare the to consume the $routes has been registered
$dispatcher = new Dispatcher($route, $container);

// Dispatch to parse the patterns to get controllers and actions from above registered route and call the respective controller
$dispatcher->dispatch($request);
