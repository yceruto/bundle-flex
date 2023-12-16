<?php

namespace Yceruto\BundleFlex\Tests\Maker;

use Yceruto\BundleFlex\Maker\BundleConfigMaker;
use Yceruto\BundleFlex\Maker\BundleOptions;

class BundleConfigMakerTest extends MakerTestCase
{
    public function testCreateConfigFileWithCorrectContent(): void
    {
        $maker = new BundleConfigMaker($this->bundleDir);
        $options = new BundleOptions();
        $options->name = 'acme/acme-bundle';

        $maker->make($options);

        $this->assertGenFile('config/services.php');
        self::assertFileDoesNotExist($this->bundleDir.'config/definition.php');
    }

    public function testCreateDefinitionFileWhenHasConfigOptionIsTrue(): void
    {
        $maker = new BundleConfigMaker($this->bundleDir);
        $options = new BundleOptions();
        $options->name = 'acme/acme-bundle';
        $options->hasConfig = true;

        $maker->make($options);

        $this->assertGenFile('config/services.php');
        $this->assertGenFile('config/definition.php');
    }
}
