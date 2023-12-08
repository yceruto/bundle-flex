<?php

namespace Yceruto\BundleFlex;

use Composer\Command\GlobalCommand;
use Composer\Composer;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Factory;
use Composer\Installer;
use Composer\Installer\SuggestedPackagesReporter;
use Composer\IO\IOInterface;
use Composer\IO\NullIO;
use Composer\Json\JsonFile;
use Composer\Json\JsonManipulator;
use Composer\Package\Locker;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;

class Flex implements PluginInterface, EventSubscriberInterface
{
    private static bool $activated = true;
    private Composer $composer;
    private IOInterface $io;
    private Installer $installer;

    public static function getSubscribedEvents(): array
    {
        if (!self::$activated) {
            return [];
        }

        return [
            ScriptEvents::POST_CREATE_PROJECT_CMD => 'configureBundle',
        ];
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->composer = $composer;
        $this->io = $io;

        $backtrace = debug_backtrace();
        foreach ($backtrace as $trace) {
            if (isset($trace['object']) && $trace['object'] instanceof Installer) {
                $this->installer = $trace['object']->setSuggestedPackagesReporter(new SuggestedPackagesReporter(new NullIO()));
            }
        }
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
        self::$activated = false;
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
        // no-op
    }

    public function configureBundle(Event $event): void
    {
        var_dump($event->getArguments());
        $this->removeSkeletonFiles();
        $this->configureComposerJson();
    }

    private function removeSkeletonFiles(): void
    {
        @unlink('LICENSE');
        @unlink('README.md');
    }

    private function configureComposerJson(): void
    {
        $file = Factory::getComposerFile();
        $contents = file_get_contents($file);
        JsonFile::parseJson($contents);

        $manipulator = new JsonManipulator($contents);
        $manipulator->addProperty('name', 'acme/acme-bundle');
        $manipulator->addProperty('description', 'Acme bundle description');
        $manipulator->removeSubNode('require', 'yceruto/bundle-flex');
        $manipulator->removeSubNode('config', 'allow-plugins');
        file_put_contents($file, $manipulator->getContents());

        //$this->updateComposerLock();
        $this->reinstall();
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

    private function reinstall(): void
    {
        $composer = Factory::create($this->io, null, false, true);

        $installer = clone $this->installer;
        $installer->__construct(
            $this->io,
            $composer->getConfig(),
            $composer->getPackage(),
            $composer->getDownloadManager(),
            $composer->getRepositoryManager(),
            $composer->getLocker(),
            $composer->getInstallationManager(),
            $composer->getEventDispatcher(),
            $composer->getAutoloadGenerator()
        );
        if (method_exists($installer, 'setPlatformRequirementFilter')) {
            $installer->setPlatformRequirementFilter(((array) $this->installer)["\0*\0platformRequirementFilter"]);
        }

        $installer->run();
    }
}
