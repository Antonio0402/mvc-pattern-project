<?php

namespace App\Framework;

class MVCViewTemplate implements TemplateViewerInterface
{
    public function render(string $template, array $args = [])
    {
        extract($args, EXTR_SKIP);

        $code = file_get_contents(VIEWS_PATH . "{$template}");

        if (preg_match('/^{% extends "(?<template>.*)" %}/', $code, $matches)) {
            $base = get_file_contents(VIEWS_PATH . "{$matches['template']}");
            $blocks = $this->getBlocks($code);
            $code = $this->getYields($base, $blocks);
        }

        $template = $this->replaceVariables($code);
        $template = $this->replacePHP($template);
        $template = $this->includeFile($template);

        ob_start();

        eval('?>' . $template);

        // require_once(VIEWS_PATH . "{$template}");
        ob_get_clean();
    }

    public function replaceVariables(string $value)
    {
        $template = preg_replace('/{{\s*(\S+)\s*}}/', '<?= htmlspecialchars(\$$1 ?? "", ENT_QUOTES, "UTF-8") ?>', $value);

        return $template;
    }

    public function replacePHP(string $code)
    {
        $template = preg_replace('/{%\s*(.+)\s*%}/', '<?php \$1 ?>', $code);

        return $template;
    }

    public function getBlocks(string $code)
    {
        $blocks = [];
        preg_match_all('/{% block (?<name>\w+) %}(?<content>.*?){% endblock %}/s', $code, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $blocks[$match['name']] = $match['content'];
        }

        return $blocks;
    }

    public function getYields(string $code, array $blocks)
    {
        preg_match_all('/{% yeild (?<name>\w+) %}/', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $name = $match['name'];
            $block = $blocks[$name];

            $code = str_replace($match[0], $block, $code);
        }
    }

    private function includeFile(string $code)
    {
        if (preg_match_all('/{% include (?<template>.*) %}/', $code, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $content = get_file_contents(VIEWS_PATH . "{$match['template']}");

                $code = preg_replace('/{% include \"$template\" %}/', $content, $code);
            }
        }
        return $code;
    }
}
