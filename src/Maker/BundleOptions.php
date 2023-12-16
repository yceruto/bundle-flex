<?php

namespace Yceruto\BundleFlex\Maker;

class BundleOptions
{
    /**
     * Composer package name
     */
    public string $name = 'vendor/name-bundle';

    /**
     * Composer package description
     */
    public string $description = 'Acme bundle description';

    /**
     * Will the bundle contain a config definition?
     */
    public bool $hasConfig = false;

    /**
     * Will the bundle contain web assets?
     */
    public bool $hasWebAssets = false;

    /**
     * Will the bundle contain twig templates?
     */
    public bool $hasTwigTemplates = false;

    /**
     * Will the bundle contain translations?
     */
    public bool $hasTranslations = false;
}
