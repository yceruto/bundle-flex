<?php

namespace Yceruto\BundleFlex;

use Composer\Command\RemoveCommand;
use Composer\Composer;
use Composer\Console\Application;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonManipulator;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class Flex implements PluginInterface, EventSubscriberInterface
{
    private static bool $activated = true;
    private Composer $composer;
    private IOInterface $io;

    public static function getSubscribedEvents(): array
    {
        if (!self::$activated) {
            return [];
        }

        return [
            ScriptEvents::POST_CREATE_PROJECT_CMD => 'postCreateBundle',
        ];
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
        self::$activated = false;
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
        // no-op
    }

    public function postCreateBundle(Event $event): void
    {
        $name = $this->io->ask('Composer package name (e.g. acme/acme-bundle): ', 'acme/acme-bundle');
        $description = $this->io->ask('Composer package description: ', 'Acme bundle description');

        $this->configureComposerJson($name, $description);
        $this->removePlugin();
        $this->removeSkeletonFiles();

        $message = <<<EOT
<bg=green;fg=white>                                            </>
<bg=green;fg=white> The bundle has been successfully created!  </>
<bg=green;fg=white>                                            </>
EOT;
        $this->io->write($message);
    }

    private function removeSkeletonFiles(): void
    {
        @unlink('LICENSE');
        @unlink('README.md');
    }

    private function configureComposerJson(string $name, string $description): void
    {
        $file = Factory::getComposerFile();

        $manipulator = new JsonManipulator(file_get_contents($file));
        $manipulator->addProperty('name', $name);
        $manipulator->addProperty('description', $description);

        file_put_contents($file, $manipulator->getContents());
    }

    private function removePlugin(): void
    {
        $command = new RemoveCommand();
        $command->setApplication(new Application());
        $command->setComposer($this->composer);
        $command->run(new ArrayInput(['packages' => ['yceruto/bundle-flex']]), new NullOutput());
    }
}
