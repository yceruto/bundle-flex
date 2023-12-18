<?php

namespace Yceruto\BundleFlex\Template;

class TemplateFileCreator
{
    public function __construct(
        private readonly string $bundleDir,
        private readonly TemplateRenderer $renderer = new TemplateRenderer(),
    ) {
    }

    public function create(string $name, array $parameters = [], string $destName = null): void
    {
        $content = $this->renderer->render($name.'.template', $parameters);
        $destination = $this->bundleDir.'/'.($destName ?? $name);

        if (!is_dir(dirname($destination)) || !file_put_contents($destination, $content)) {
            throw new \RuntimeException(sprintf('Unable to create "%s" file.', $destination));
        }
    }
}
