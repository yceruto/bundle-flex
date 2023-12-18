<?php

namespace Yceruto\BundleFlex\Maker;

use Yceruto\BundleFlex\Composer\CommandRunner;

class BundleDirectoryMaker
{
    public function __construct(
        private readonly CommandRunner $commandRunner,
        private readonly string $bundleDir,
    ) {
    }

    public function make(BundleOptions $options): void
    {
        $this->makeDirectory('config');
        $this->makeDirectory('docs');
        $this->makeDirectory('src');
        $this->makeDirectory('tests');

        if ($options->hasWebAssets) {
            $this->makeDirectory('assets');
            $this->makeDirectory('public');
        }

        if ($options->hasTwigTemplates) {
            $this->makeDirectory('templates');
            $this->commandRunner->require('symfony/twig-bundle');
        }

        if ($options->hasTranslations) {
            $this->makeDirectory('translations');
        }

        if ($options->hasControllers) {
            $this->makeDirectory('src/Controller');
        }
    }

    private function makeDirectory(string $name): void
    {
        $dir = $this->bundleDir.'/'.$name;

        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created.', $dir));
        }
    }
}
