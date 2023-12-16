<?php

namespace Yceruto\BundleFlex\Maker;

use Composer\IO\IOInterface;

class FlexMaker
{
    private readonly BundleOptionsAsker $bundleOptionsAsker;
    private readonly BundleComposerJsonMaker $bundleComposerJsonMaker;
    private readonly BundleReadmeMaker $bundleReadmeMaker;
    private readonly BundleDirectoryMaker $bundleDirectoryMaker;
    private readonly BundleMaker $bundleMaker;
    private readonly BundleConfigMaker $bundleConfigMaker;

    public function __construct(IOInterface $io, string $bundleDir)
    {
        $this->bundleOptionsAsker = new BundleOptionsAsker($io);
        $this->bundleComposerJsonMaker = new BundleComposerJsonMaker($bundleDir);
        $this->bundleReadmeMaker = new BundleReadmeMaker($bundleDir);
        $this->bundleDirectoryMaker = new BundleDirectoryMaker($bundleDir);
        $this->bundleMaker = new BundleMaker($bundleDir);
        $this->bundleConfigMaker = new BundleConfigMaker($bundleDir);
    }

    public function make(): void
    {
        $options = $this->bundleOptionsAsker->ask();

        $this->bundleComposerJsonMaker->make($options);
        $this->bundleReadmeMaker->make($options);
        $this->bundleDirectoryMaker->make($options);
        $this->bundleMaker->make($options);
        $this->bundleConfigMaker->make($options);
    }
}
