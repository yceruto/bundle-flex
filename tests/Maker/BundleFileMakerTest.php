<?php

namespace Yceruto\BundleFlex\Tests\Maker;

use Yceruto\BundleFlex\Maker\BundleDirectoryMaker;
use Yceruto\BundleFlex\Maker\BundleFileMaker;
use Yceruto\BundleFlex\Maker\BundleOptions;
use Yceruto\BundleFlex\Template\TemplateFileCreator;

class BundleFileMakerTest extends MakerTestCase
{
    public function testCreateBundleFilesWithCorrectContent(): void
    {
        $maker = new BundleFileMaker(new TemplateFileCreator($this->bundleDir));
        $options = new BundleOptions();
        $options->name = 'acme/acme-bundle';
        $options->hasConfig = true;
        $options->hasControllers = true;

        (new BundleDirectoryMaker($this->bundleDir))->make($options);
        $maker->make($options);

        $this->assertGenFile('config/routes.php');
        $this->assertGenFile('config/definition.php');
        $this->assertGenFile('config/services.php');
        $this->assertGenFile('docs/index.md');
        $this->assertGenFile('src/Controller/DefaultController.php');
        $this->assertGenFile('README.md');
    }

    public function testThrowsExceptionWhenUnableToCreateReadmeFile(): void
    {
        $maker = new BundleFileMaker(new TemplateFileCreator('/invalid/path/to/bundle'));
        $options = new BundleOptions();
        $options->name = 'acme/acme-bundle';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to create "/invalid/path/to/bundle/README.md" file.');

        $maker->make($options);
    }
}
