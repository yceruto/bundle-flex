<?php

namespace Yceruto\BundleFlex\Maker;

use Composer\Json\JsonManipulator;
use Yceruto\BundleFlex\Inflector;

class BundleComposerJsonMaker
{
    public function __construct(private readonly string $bundleDir)
    {
    }

    public function make(BundleOptions $options): void
    {
        $file = $this->bundleDir.'/composer.json';

        $manipulator = new JsonManipulator(file_get_contents($file));
        $manipulator->addProperty('name', $options->name);
        $manipulator->addProperty('description', $options->description);
        $manipulator->addSubNode('autoload', 'psr-4', [Inflector::namespacefy($options->name).'\\' => 'src/']);
        $manipulator->addSubNode('autoload-dev', 'psr-4', [Inflector::namespacefy($options->name).'\\Tests\\' => 'tests/']);

        file_put_contents($file, $manipulator->getContents());
    }
}
