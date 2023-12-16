<?php

namespace Yceruto\BundleFlex\Maker;

use Composer\IO\IOInterface;
use ReflectionProperty;

class BundleOptionsAsker
{
    public function __construct(private readonly IOInterface $io)
    {
    }

    public function ask(): BundleOptions
    {
        $options = new BundleOptions();
        $reflector = new \ReflectionClass($options);

        $this->io->write(' ');
        foreach ($options as $name => $default) {
            $question = sprintf(
                '%s (%s): ',
                $this->humanizeProperty($reflector->getProperty($name)),
                $this->humanizeValue($default),
            );

            if (is_bool($default)) {
                $options->$name = $this->io->askConfirmation($question, $default);
            } else {
                $options->$name = $this->io->ask($question, $default);
            }
        }

        return $options;
    }

    private function humanizeProperty(ReflectionProperty $property): string
    {
        return trim(preg_replace('/\/\*\*|\*\/|\n\s*\* ?/', '', $property->getDocComment()), " \t\n\r\0\x0B/");
    }

    private function humanizeValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'y' : 'n';
        }

        return (string) $value;
    }
}
