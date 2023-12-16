<?php

namespace Yceruto\BundleFlex\Maker;

use Yceruto\BundleFlex\Template\TemplateFileCreator;
use Yceruto\BundleFlex\Utils\Inflector;

class BundleFileMaker
{
    public function __construct(private readonly TemplateFileCreator $templateFileCreator)
    {
    }

    public function make(BundleOptions $options): void
    {
        $this->templateFileCreator->create('README.md.template', [
            'package-name' => $options->name,
            'bundle-class' => Inflector::namespacefy($options->name).'\\'.Inflector::className($options->name),
        ]);

        $this->templateFileCreator->create('docs/index.md.template', [
            'bundle-name' => Inflector::className($options->name),
        ]);

        if ($options->hasConfig) {
            $this->templateFileCreator->create('config/definition.php.template');
        }

        $this->templateFileCreator->create('config/services.php.template', [
            'vendor' => Inflector::vendory($options->name),
        ]);
    }
}
