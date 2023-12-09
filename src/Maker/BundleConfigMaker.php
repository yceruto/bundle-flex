<?php

namespace Yceruto\BundleFlex\Maker;

use Composer\Factory;

class BundleConfigMaker
{
    public function make(bool $hasDefinition): void
    {
        $directory = \dirname(Factory::getComposerFile()).'/config';

        if (!is_dir($directory) && !mkdir($directory) && !is_dir($directory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created.', $directory));
        }

        if ($hasDefinition) {
            $this->makeDefinitionFile($directory);
        }

        $this->makeServiceFile($directory);
    }

    private function makeDefinitionFile(string $directory): void
    {
        if (!copy(__DIR__.'/../../templates/definition.php.tpl', $directory.'/definition.php')) {
            throw new \RuntimeException('Unable to copy definition.php.tpl file.');
        }
    }

    private function makeServiceFile(string $directory): void
    {
        if (!copy(__DIR__.'/../../templates/services.php.tpl', $directory.'/services.php')) {
            throw new \RuntimeException('Unable to copy services.php.tpl file.');
        }
    }
}
