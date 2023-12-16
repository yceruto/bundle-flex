<?php

namespace Yceruto\BundleFlex\Maker;

use Yceruto\BundleFlex\Inflector;

class BundleReadmeMaker
{
    public function __construct(private readonly string $bundleDir)
    {
    }

    public function make(BundleOptions $options): void
    {
        $readme = file_get_contents(__DIR__.'/../../templates/README.md.template');
        $readme = str_replace(
            ['<package-name>', '<bundle-class>'],
            [$options->name, Inflector::namespacefy($options->name).'\\'.Inflector::className($options->name)],
            $readme,
        );

        if (!is_dir($this->bundleDir) || !file_put_contents($this->bundleDir.'/README.md', $readme)) {
            throw new \RuntimeException('Unable to create README.md file.');
        }
    }
}
