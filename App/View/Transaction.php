<?php

namespace App\View;

class Transaction
{
    public function render(string $template, array $args = [])
    {
        extract($args, EXTR_SKIP);

        ob_start();
        require_once(VIEWS_PATH . "{$template}");
        ob_get_clean();
    }
}
