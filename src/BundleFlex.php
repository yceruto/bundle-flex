<?php

namespace Yceruto\BundleFlex;

use Composer\Composer;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;

class BundleFlex implements PluginInterface, EventSubscriberInterface
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
        // Remove LICENSE (which do not apply to the user bundle)
        @unlink('LICENSE');
    }
}
