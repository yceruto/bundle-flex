<?php

namespace Yceruto\BundleFlex\Tests\Maker;

use Yceruto\BundleFlex\Maker\BundleDirectoryMaker;
use Yceruto\BundleFlex\Maker\BundleOptions;

class BundleDirectoryMakerTest extends MakerTestCase
{
    public function testMakeDefault(): void
    {
        $maker = new BundleDirectoryMaker($this->bundleDir);
        $maker->make(new BundleOptions());

        $this->assertFileDoesNotExist($this->bundleDir.'/assets');
        $this->assertFileDoesNotExist($this->bundleDir.'/config');
        $this->assertFileDoesNotExist($this->bundleDir.'/public');
        $this->assertFileDoesNotExist($this->bundleDir.'/templates');
        $this->assertFileDoesNotExist($this->bundleDir.'/translation');
    }

    public function testMakeWebAssets(): void
    {
        $maker = new BundleDirectoryMaker($this->bundleDir);
        $options = new BundleOptions();
        $options->hasWebAssets = true;

        $maker->make($options);

        $this->assertDirectoryExists($this->bundleDir.'/assets');
        $this->assertDirectoryExists($this->bundleDir.'/public');
    }

    public function testMakeConfig(): void
    {
        $maker = new BundleDirectoryMaker($this->bundleDir);
        $options = new BundleOptions();
        $options->hasConfig = true;

        $maker->make($options);

        $this->assertDirectoryExists($this->bundleDir.'/config');
    }

    public function testMakeTwigTemplates(): void
    {
        $maker = new BundleDirectoryMaker($this->bundleDir);
        $options = new BundleOptions();
        $options->hasTwigTemplates = true;

        $maker->make($options);

        $this->assertDirectoryExists($this->bundleDir.'/templates');
    }

    public function testMakeTranslations(): void
    {
        $maker = new BundleDirectoryMaker($this->bundleDir);
        $options = new BundleOptions();
        $options->hasTranslations = true;

        $maker->make($options);

        $this->assertDirectoryExists($this->bundleDir.'/translations');
    }
}
