<?php

namespace Yceruto\BundleFlex;

use Composer\Command\RemoveCommand;
use Composer\Composer;
use Composer\Console\Application;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Yceruto\BundleFlex\Maker\FlexMaker;

class Flex implements PluginInterface, EventSubscriberInterface
{
    private FlexMaker $maker;
    private Composer $composer;
    private IOInterface $io;

    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_CREATE_PROJECT_CMD => 'onPostCreateProject',
        ];
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->maker = new FlexMaker($io);
        $this->composer = $composer;
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
        $this->maker->make();
        $this->removePlugin();
        $this->removeSkeletonFiles();
        $this->writeSuccessMessage();
    }

    private function removePlugin(): void
    {
        $command = new RemoveCommand();
        $command->setApplication(new Application());
        $command->setComposer($this->composer);
        $command->run(new ArrayInput(['packages' => ['yceruto/bundle-flex'], '--dev']), new NullOutput());
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
