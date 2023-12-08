<?php

namespace Yceruto\BundleFlex;

use Composer\Command\RemoveCommand;
use Composer\Composer;
use Composer\DependencyResolver\Operation\UninstallOperation;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Json\JsonManipulator;
use Composer\Package\Locker;
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
        $this->removeSkeletonFiles();
        //$this->configureComposerJson();
        $this->removeBundleFlexPlugin();
    }

    private function removeSkeletonFiles(): void
    {
        @unlink('LICENSE');
        @unlink('README.md');
    }

    private function configureComposerJson(): void
    {
        $file = Factory::getComposerFile();

        $manipulator = new JsonManipulator(file_get_contents($file));
        $manipulator->addProperty('name', 'acme/acme-bundle');
        $manipulator->addProperty('description', 'Acme bundle description');
        $manipulator->removeSubNode('require', 'yceruto/bundle-flex');
        $manipulator->removeSubNode('config', 'allow-plugins');

        file_put_contents($file, $manipulator->getContents());
    }

    private function updateComposerLock(): void
    {
        $lock = substr(Factory::getComposerFile(), 0, -4).'lock';
        $composerJson = file_get_contents(Factory::getComposerFile());
        $lockFile = new JsonFile($lock, null, $this->io);
        $locker = new Locker($this->io, $lockFile, $this->composer->getInstallationManager(), $composerJson);
        $lockData = $locker->getLockData();
        $lockData['content-hash'] = Locker::getContentHash($composerJson);
        $lockFile->write($lockData);
    }

    private function removeBundleFlexPlugin(): void
    {
        $command = new RemoveCommand();
        $command->setComposer($this->composer);
        $command->run(new ArrayInput(['packages' => ['yceruto/bundle-flex']]), new NullOutput());
    }
}
