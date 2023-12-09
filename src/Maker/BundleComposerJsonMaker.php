<?php

namespace Yceruto\BundleFlex\Maker;

use Composer\Factory;
use Composer\Json\JsonManipulator;
use Yceruto\BundleFlex\Inflector;

class BundleComposerJsonMaker
{
    public function make(string $name, string $description): void
    {
        $file = Factory::getComposerFile();

        $manipulator = new JsonManipulator(file_get_contents($file));
        $manipulator->addProperty('name', $name);
        $manipulator->addProperty('description', $description);
        $manipulator->addSubNode('autoload.psr-4', Inflector::namespacefy($name).'\\', 'src/');
        $manipulator->addSubNode('autoload-dev.psr-4', Inflector::namespacefy($name).'\\Tests\\', 'tests/');

        file_put_contents($file, $manipulator->getContents());
    }
}
