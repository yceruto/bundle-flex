<?php

namespace Yceruto\BundleFlex\Template;

class TemplateRenderer
{
    public function render(string $template, array $parameters = []): string
    {
        $filename = __DIR__.'/../../templates/'.$template;

        if (!file_exists($filename)) {
            throw new \InvalidArgumentException(sprintf('Template "%s" not found.', $template));
        }

        $content = file_get_contents($filename);

        if ($parameters) {
            $placeholders = array_map(static fn (string $name) => '<'.$name.'>', array_keys($parameters));
            $content = str_replace($placeholders, array_values($parameters), $content);
        }

        return $content;
    }
}
