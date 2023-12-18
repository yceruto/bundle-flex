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

        if ($options->hasWebAssets) {
            $this->fileCreator->create('assets/acme-bundle.js', [
                'bundle-name' => Inflector::className($options->name),
            ], 'assets/'.Inflector::fileName($options->name, 'js'));

            $this->fileCreator->create('public/acme-bundle.min.js', [
                'bundle-name' => Inflector::className($options->name),
            ], 'public/'.Inflector::fileName($options->name, 'min.js'));
        }

        if ($options->hasConfig) {
            $this->fileCreator->create('config/definition.php');
        }

        $this->fileCreator->create('config/services.php', [
            'vendor-prefix' => Inflector::vendory($options->name),
        ]);

        $this->fileCreator->create('docs/index.md', [
            'bundle-name' => Inflector::className($options->name),
        ]);

        if ($options->hasControllers) {
            $this->fileCreator->create('config/routes.php', [
                'bundle-namespace' => Inflector::namespacefy($options->name),
                'vendor-prefix' => Inflector::vendory($options->name),
            ]);

            $this->fileCreator->create('src/Controller/HelloController.php', [
                'bundle-namespace' => Inflector::namespacefy($options->name),
                'bundle-name' => Inflector::className($options->name),
            ]);
        }

        if ($options->hasTwigTemplates) {
            $this->fileCreator->create('templates/hello.html.twig', [
                'bundle-name' => Inflector::className($options->name),
            ]);
        }

        if ($options->hasTranslations) {
            $this->fileCreator->create('translations/AcmeBundle.fr.xlf', [
                'vendor-prefix' => Inflector::vendory($options->name),
            ]);
        }
    }
}
