<?php

namespace Yceruto\BundleFlex\Tests\Maker;

use PHPUnit\Framework\TestCase;
use Yceruto\BundleFlex\Maker\BundleDirectoryMaker;

abstract class MakerTestCase extends TestCase
{
    protected BundleDirectoryMaker $maker;
    protected string $bundleDir;
    protected string $expectedDir;

    protected function setUp(): void
    {
        $this->expectedDir = __DIR__.'/../Expected/acme-bundle';
        $this->bundleDir = sys_get_temp_dir().'/bundle-flex/bundles/acme-bundle';
        if (!is_dir($this->bundleDir)) {
            mkdir($this->bundleDir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        if (is_dir($this->bundleDir)) {
            $this->rmdir($this->bundleDir);
        }
    }

    protected function assertGenFile(string $relativePath, bool $save = false): void
    {
        if ($save) {
            file_put_contents($this->expectedDir.'/'.$relativePath.'.expected', file_get_contents($this->bundleDir.'/'.$relativePath));
        }

        self::assertFileEquals($this->expectedDir.'/'.$relativePath.'.expected', $this->bundleDir.'/'.$relativePath);
    }

    private function rmdir($dir): bool
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ('.' === $item || '..' === $item) {
                continue;
            }

            if (!$this->rmdir($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }
}
