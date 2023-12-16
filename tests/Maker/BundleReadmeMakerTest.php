<?php

namespace Yceruto\BundleFlex\Tests\Maker;

use Yceruto\BundleFlex\Maker\BundleOptions;
use Yceruto\BundleFlex\Maker\BundleReadmeMaker;

class BundleReadmeMakerTest extends MakerTestCase
{
    public function testCreateReadmeFileWithCorrectContent(): void
    {
        $maker = new BundleReadmeMaker($this->bundleDir);
        $options = new BundleOptions();
        $options->name = 'acme/acme-bundle';

        $maker->make($options);

        $this->assertGenFile('README.md');
    }

    public function testThrowsExceptionWhenUnableToCreateReadmeFile(): void
    {
        $maker = new BundleReadmeMaker('/invalid/path/to/bundle');
        $options = new BundleOptions();
        $options->name = 'acme/acme-bundle';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to create README.md file.');

        $maker->make($options);
    }
}
