<?php

namespace App\Framework;

class Request
{
    public function __construct(
        public string $uri,
        public string $method,
        public array $get,
        public array $post,
        public array $files,
        public array $server,
        public array $cookies
    ) {

    }

    public static function getAllFromGlobals(): self
    {
        return new static(
            $_SERVER['REQUEST_URI'],
            $_SERVER['REQUEST_METHOD'],
            $_GET,
            $_POST,
            $_FILES,
            $_SERVER,
            $_COOKIE
        );
    }
}
