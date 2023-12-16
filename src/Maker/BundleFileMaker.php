<?php

namespace Yceruto\BundleFlex\Maker;

use Yceruto\BundleFlex\Template\TemplateFileCreator;
use Yceruto\BundleFlex\Utils\Inflector;

class BundleFileMaker
{
    public function __construct(private readonly TemplateFileCreator $fileCreator)
    {
    }

    public function make(BundleOptions $options): void
    {
        $this->fileCreator->create('README.md', [
            'bundle-name' => Inflector::className($options->name),
            'package-name' => $options->name,
            'bundle-class' => Inflector::namespacefy($options->name).'\\'.Inflector::className($options->name),
        ]);

        $this->fileCreator->create('docs/index.md', [
            'bundle-name' => Inflector::className($options->name),
        ]);

        if ($options->hasConfig) {
            $this->fileCreator->create('config/definition.php');
        }

        $this->fileCreator->create('config/services.php', [
            'vendor' => Inflector::vendory($options->name),
        ]);

        if ($options->hasControllers) {
            $this->fileCreator->create('config/routes.php', [
                'bundle-namespace' => Inflector::namespacefy($options->name),
                'vendor' => Inflector::vendory($options->name),
            ]);

            $this->fileCreator->create('src/Controller/DefaultController.php', [
                'bundle-namespace' => Inflector::namespacefy($options->name),
                'bundle-name' => Inflector::className($options->name),
            ]);
        }
    }
}
