<?php

namespace Yceruto\BundleFlex\Tests\Maker;

use Composer\IO\IOInterface;
use Composer\IO\NullIO;
use PHPUnit\Framework\TestCase;
use Yceruto\BundleFlex\Maker\BundleOptionsAsker;

class BundleOptionsAskerTest extends TestCase
{
    public function testAskForOptionsAndReturnBundleOptions(): void
    {
        $io = $this->createMock(IOInterface::class);
        $io->method('ask')->willReturnMap([
            ['Composer package name (vendor/name-bundle): ', 'vendor/name-bundle', 'foo/bar-bundle'],
            ['Composer package description (Acme bundle description): ', 'Acme bundle description', 'Foo bar bundle description'],
        ]);
        $io->method('askConfirmation')->willReturnMap([
            ['Will the bundle contain a config definition? (n): ', false, true],
            ['Will the bundle contain Web assets? (n): ', false, true],
            ['Will the bundle contain Twig templates? (n): ', false, true],
            ['Will the bundle contain translations? (n): ', false, true],
        ]);

        $asker = new BundleOptionsAsker($io);
        $options = $asker->ask();

        self::assertSame('foo/bar-bundle', $options->name);
        self::assertSame('Foo bar bundle description', $options->description);
        self::assertTrue($options->hasConfig);
        self::assertTrue($options->hasWebAssets);
        self::assertTrue($options->hasTwigTemplates);
        self::assertTrue($options->hasTranslations);
    }

    public function testHandleDefaultValuesCorrectly(): void
    {
        $asker = new BundleOptionsAsker(new NullIO());
        $options = $asker->ask();

        self::assertSame('vendor/name-bundle', $options->name);
        self::assertFalse($options->hasConfig);
    }
}
