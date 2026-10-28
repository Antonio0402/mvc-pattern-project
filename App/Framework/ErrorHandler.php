<?php

namespace App\Framework;

use Throwable;
use RuntimeException;
use App\Framework\Exceptions\PageNotFoundException;

class ErrorHandler
{
    public static function handleError(
        int $errno,
        string $errstr,
        ?string $errfile = null,
        ?int $errline = null
    ) {
        $message = $errstr;

        if ($errfile !== null) {
            $message .= ' in ' . $errfile;
        }
        if ($errno !== null) {
            $message .= ' on line ' . $errline;
        }

        throw new RuntimeException($message);
    }

    public static function handleException(Throwable $exception)
    {

        if ($exception instanceof PageNotFoundException) {
            http_response_code(404);
            $template = '404';
        } else {
            http_response_code(500);
            $template = '500.php';
        }

        if ($_ENV['SHOW_ERRORS'] === 'true') {
            ini_set('display_errors', 1);
        } else {
            ini_set('display_errors', 0);
            init_set('log_errors', 1);
            echo init_get('error_log');
        }
        $displayErrors = (bool)ini_get('display_errors');
        require VIEWS_PATH . "{$template}";

        throw $exception;
    }
}
