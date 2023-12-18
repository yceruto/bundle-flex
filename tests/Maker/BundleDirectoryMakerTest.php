<?php

namespace Yceruto\BundleFlex\Tests\Maker;

use Composer\Composer;
use Yceruto\BundleFlex\Composer\CommandRunner;
use Yceruto\BundleFlex\Maker\BundleDirectoryMaker;
use Yceruto\BundleFlex\Maker\BundleOptions;

class BundleDirectoryMakerTest extends MakerTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $commandRunner = $this->createMock(CommandRunner::class);
        $this->maker = new BundleDirectoryMaker($commandRunner, $this->bundleDir);
    }

    public function testMakeDefault(): void
    {
        $this->maker->make(new BundleOptions());

        $this->assertFileExists($this->bundleDir.'/config');
        $this->assertFileExists($this->bundleDir.'/src');
        $this->assertFileExists($this->bundleDir.'/tests');
        $this->assertFileDoesNotExist($this->bundleDir.'/assets');
        $this->assertFileDoesNotExist($this->bundleDir.'/public');
        $this->assertFileDoesNotExist($this->bundleDir.'/templates');
        $this->assertFileDoesNotExist($this->bundleDir.'/translation');
    }

    public function testMakeWebAssets(): void
    {
        $options = new BundleOptions();
        $options->hasWebAssets = true;

        $this->maker->make($options);

        $this->assertDirectoryExists($this->bundleDir.'/assets');
        $this->assertDirectoryExists($this->bundleDir.'/public');
    }

    public function testMakeConfig(): void
    {
        $options = new BundleOptions();
        $options->hasConfig = true;

        $this->maker->make($options);

        $this->assertDirectoryExists($this->bundleDir.'/config');
    }

    public function testMakeTwigTemplates(): void
    {
        $options = new BundleOptions();
        $options->hasTwigTemplates = true;

        $this->maker->make($options);

        $this->assertDirectoryExists($this->bundleDir.'/templates');
    }

    public function testMakeTranslations(): void
    {
        $options = new BundleOptions();
        $options->hasTranslations = true;

        $this->maker->make($options);

        $this->assertDirectoryExists($this->bundleDir.'/translations');
    }
}
