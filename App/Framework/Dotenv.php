<?php

namespace App\Framework;

class Dotenv
{
    public function load(string $path)
    {
        if (! file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);

            if ($key === '') {
                continue;
            }

            $_ENV[$key] = trim($value, " \t\n\r\0\x0B\"'");
        }
    }
}
