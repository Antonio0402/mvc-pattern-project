<?php

namespace App\Framework;

interface TemplateViewerInterface
{
    public function render(string $template, array $args = []): void;
}
