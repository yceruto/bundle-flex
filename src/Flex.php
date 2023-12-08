<?php

namespace Yceruto\BundleFlex;

use Composer\Composer;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Json\JsonManipulator;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;

class Flex implements PluginInterface, EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_CREATE_PROJECT_CMD => 'configureBundle',
        ];
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
        // TODO: Implement activate() method.
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
        // no-op
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
        // no-op
    }

    public function configureBundle(Event $event): void
    {
        // Remove files that do not apply to the user bundle
        @unlink('LICENSE');
        @unlink('README.md');

        $this->configureComposerJson();
    }

    private function configureComposerJson(): void
    {
        $file = Factory::getComposerFile();
        $contents = file_get_contents($file);
        JsonFile::parseJson($contents);

        $manipulator = new JsonManipulator($contents);
        $manipulator->addProperty('name', 'acme/acme-bundle');
        $manipulator->addProperty('description', 'Acme bundle description');
        file_put_contents($file, $manipulator->getContents());
    }
}
