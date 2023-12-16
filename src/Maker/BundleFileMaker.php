<?php

namespace Yceruto\BundleFlex\Maker;

use Yceruto\BundleFlex\Template\TemplateCloner;
use Yceruto\BundleFlex\Utils\Inflector;

class BundleFileMaker
{
    public function __construct(private readonly TemplateCloner $templateCloner)
    {
    }

    public function make(BundleOptions $options): void
    {
        $this->templateCloner->clone('README.md.template', [
            'package-name' => $options->name,
            'bundle-class' => Inflector::namespacefy($options->name).'\\'.Inflector::className($options->name),
        ]);

        $this->templateCloner->clone('docs/index.md.template', [
            'bundle-name' => Inflector::className($options->name),
        ]);

        if ($options->hasConfig) {
            $this->templateCloner->clone('config/definition.php.template');
        }

        $this->templateCloner->clone('config/services.php.template', [
            'vendor' => Inflector::vendory($options->name),
        ]);
    }
}
