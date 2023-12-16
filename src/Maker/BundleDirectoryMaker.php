<?php

namespace Yceruto\BundleFlex\Maker;

class BundleDirectoryMaker
{
    public function __construct(private readonly string $bundleDir)
    {
    }

    public function make(BundleOptions $options): void
    {
        if ($options->hasWebAssets) {
            $this->makeDirectory('assets');
            $this->makeDirectory('public');
        }

        if ($options->hasConfig) {
            $this->makeDirectory('config');
        }

        if ($options->hasTwigTemplates) {
            $this->makeDirectory('templates');
        }

        if ($options->hasTranslations) {
            $this->makeDirectory('translation');
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
