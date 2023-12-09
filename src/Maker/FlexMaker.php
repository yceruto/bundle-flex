<?php

namespace Yceruto\BundleFlex\Maker;

use Composer\IO\IOInterface;

class FlexMaker
{
    private BundleMaker $bundleMaker;
    private BundleConfigMaker $bundleConfigMaker;
    private BundleComposerJsonMaker $composerJsonMaker;
    private IOInterface $io;

    public function __construct(IOInterface $io)
    {
        $this->bundleMaker = new BundleMaker();
        $this->bundleConfigMaker = new BundleConfigMaker();
        $this->composerJsonMaker = new BundleComposerJsonMaker();
        $this->io = $io;
    }

    public function make(): void
    {
        $this->io->write(' ');
        $name = $this->io->ask('Composer package name (e.g. vendor/name-bundle): ', 'acme/acme-bundle');
        $description = $this->io->ask('Composer package description: ', 'Acme bundle description');
        $hasDefinition = $this->io->askConfirmation('Will the bundle contain a config definition? (y,n): ');

        $this->composerJsonMaker->make($name, $description);
        $this->bundleMaker->make($name, $hasDefinition);
        $this->bundleConfigMaker->make($hasDefinition);
    }
}
