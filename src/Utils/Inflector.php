<?php

namespace Yceruto\BundleFlex\Utils;

class Inflector
{
    /**
     * Converts a word into the format for a PHP class name. Converts 'acme-bundle' to 'AcmeBundle'.
     */
    public static function classify(string $word): string
    {
        return str_replace([' ', '_', '-'], '', ucwords($word, ' _-/'));
    }

    /**
     * Converts a composer package name into the format for a PHP namespace. Converts 'acme/acme-bundle' to 'Acme\AcmeBundle'.
     */
    public static function namespacefy(string $name): string
    {
        return str_replace('/', '\\', self::classify($name));
    }

    /**
     * Converts a composer package name into the format for a PHP class name. Converts 'acme/acme-bundle' to 'AcmeBundle'.
     */
    public static function className(string $name): string
    {
        return self::classify(substr($name, strrpos($name, '/') + 1));
    }

    /**
     * Converts 'acme/foo-bundle' to 'acme_foo'.
     */
    public static function vendory(string $name): string
    {
        if (!str_contains($name, '/')) {
            return '';
        }

        return strtolower(str_replace('/', '_', substr($name, 0, strpos($name, '-'))));
    }

    public static function fileName(string $name): string
    {
        return strtolower(substr($name, strrpos($name, '/') + 1));
    }
}
