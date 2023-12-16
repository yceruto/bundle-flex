<?php

namespace Yceruto\BundleFlex\Tests\Maker;

use Yceruto\BundleFlex\Maker\BundleComposerJsonMaker;
use Yceruto\BundleFlex\Maker\BundleOptions;

class BundleComposerJsonMakerTest extends MakerTestCase
{
    public function testCreateComposerJsonFileWithCorrectContent(): void
    {
        file_put_contents($this->bundleDir.'/composer.json', '{}');
        $maker = new BundleComposerJsonMaker($this->bundleDir);
        $options = new BundleOptions();
        $options->name = 'acme/acme-bundle';

        $maker->make($options);

        $this->assertGenFile('composer.json');
    }
}
