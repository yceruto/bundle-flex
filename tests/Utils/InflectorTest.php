<?php

namespace Yceruto\BundleFlex\Tests\Utils;

use PHPUnit\Framework\TestCase;
use Yceruto\BundleFlex\Utils\Inflector;

class InflectorTest extends TestCase
{
    public function testClassifyBundleName(): void
    {
        self::assertSame('AcmeBundle', Inflector::classify('acme-bundle'));
    }

    public function testNamespacefyPackageName(): void
    {
        self::assertSame('Acme\\AcmeBundle', Inflector::namespacefy('acme/acme-bundle'));
    }

    public function testClassNamePackageName(): void
    {
        self::assertSame('AcmeBundle', Inflector::className('acme/acme-bundle'));
    }

    public function testVendoryPackageName(): void
    {
        self::assertSame('acme_foo', Inflector::vendory('acme/foo-bundle'));
    }

    public function testFileName(): void
    {
        self::assertSame('acme-bundle', Inflector::fileName('acme/acme-bundle'));
    }
}
