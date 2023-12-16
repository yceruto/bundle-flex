<?php

namespace Yceruto\BundleFlex\Maker;

use Yceruto\BundleFlex\Inflector;

class BundleConfigMaker
{
    public function __construct(private readonly string $bundleDir)
    {
    }

    public function make(BundleOptions $options): void
    {
        $directory = $this->bundleDir.'/config';

        if (!is_dir($directory) && !mkdir($directory) && !is_dir($directory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created.', $directory));
        }

        if ($options->hasConfig) {
            $this->makeDefinitionFile($directory);
        }

        $this->makeServiceFile($options, $directory);
    }

    private function makeDefinitionFile(string $directory): void
    {
        if (!copy(__DIR__.'/../../templates/config/definition.php.template', $directory.'/definition.php')) {
            throw new \RuntimeException('Unable to copy definition.php file.');
        }
    }

    private function makeServiceFile(BundleOptions $options, string $directory): void
    {
        $servicesContent = file_get_contents(__DIR__.'/../../templates/config/services.php.template');
        $servicesContent = str_replace('<vendor>', Inflector::vendory($options->name), $servicesContent);

        if (!file_put_contents($directory.'/services.php', $servicesContent)) {
            throw new \RuntimeException('Unable to create services.php file.');
        }
    }
}
