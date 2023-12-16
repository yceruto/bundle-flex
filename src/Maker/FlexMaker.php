<?php

namespace Yceruto\BundleFlex\Maker;

use Composer\IO\IOInterface;

class FlexMaker
{
    private readonly BundleMaker $bundleMaker;
    private readonly BundleConfigMaker $bundleConfigMaker;
    private readonly BundleComposerJsonMaker $bundleComposerJsonMaker;
    private readonly BundleOptionsAsker $bundleOptionsAsker;

    public function __construct(IOInterface $io, string $bundleDir)
    {
        $this->bundleMaker = new BundleMaker($bundleDir);
        $this->bundleConfigMaker = new BundleConfigMaker($bundleDir);
        $this->bundleComposerJsonMaker = new BundleComposerJsonMaker($bundleDir);
        $this->bundleOptionsAsker = new BundleOptionsAsker($io);
    }

    public function make(): void
    {
        $options = $this->bundleOptionsAsker->ask();

        $this->bundleComposerJsonMaker->make($options);
        $this->bundleMaker->make($options);
        $this->bundleConfigMaker->make($options);
    }
}
