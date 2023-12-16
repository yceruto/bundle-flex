<?php

namespace Yceruto\BundleFlex;

use Composer\Composer;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Yceruto\BundleFlex\Maker\FlexMaker;

class Flex implements PluginInterface, EventSubscriberInterface
{
    private FlexMaker $maker;
    private IOInterface $io;

    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_CREATE_PROJECT_CMD => 'onPostCreateProject',
        ];
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->maker = new FlexMaker($io, \dirname(Factory::getComposerFile()));
        $this->io = $io;
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
        // no-op
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
        // no-op
    }

    public function onPostCreateProject(Event $event): void
    {
        $this->removeSkeletonFiles();
        $this->maker->make();
        $this->writeSuccessMessage();
    }

    private function removeSkeletonFiles(): void
    {
        @unlink('LICENSE');
        @unlink('README.md');
    }

    private function writeSuccessMessage(): void
    {
        $message = <<<EOT

<bg=green;fg=white>                                                </>
<bg=green;fg=white> ✨ Your bundle has been successfully created!  </>
<bg=green;fg=white>                                                </>

EOT;
        $this->io->write($message);
    }
}
