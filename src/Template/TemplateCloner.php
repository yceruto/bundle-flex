<?php

namespace Yceruto\BundleFlex\Template;

class TemplateCloner
{
    public function __construct(
        private readonly string $bundleDir,
        private readonly TemplateRenderer $renderer = new TemplateRenderer(),
    ) {
    }

    public function clone(string $template, array $parameters = []): void
    {
        $content = $this->renderer->render($template, $parameters);
        $filename = $this->bundleDir.'/'.str_replace('.template', '', $template);

        if (!is_dir(dirname($filename)) || !file_put_contents($filename, $content)) {
            throw new \RuntimeException(sprintf('Unable to create "%s" file.', $filename));
        }
    }
}
