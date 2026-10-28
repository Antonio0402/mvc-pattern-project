<?php

namespace App\Framework;

class PHPViewTemplate implements TemplateViewerInterface
{
    public function render(string $template, array $args = []): void
    {
        extract($args, EXTR_SKIP);

        ob_start();
        require_once(VIEWS_PATH . "{$template}");
        ob_get_clean();
    }
}
