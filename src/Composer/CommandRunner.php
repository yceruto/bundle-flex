<?php

namespace Yceruto\BundleFlex\Composer;

use Composer\Command\BaseCommand;
use Composer\Command\RemoveCommand;
use Composer\Command\RequireCommand;
use Composer\Composer;
use Composer\Console\Application;
use Composer\Factory;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

class CommandRunner
{
    private readonly OutputInterface $output;

    public function __construct(
        private readonly Composer $composer,
        private readonly Application $application = new Application(),
        OutputInterface $output = null,
    ) {
        $this->output = $output ?? Factory::createOutput();
    }

    public function require(string|array $packages, bool $dev = false): int
    {
        return $this->run(new RequireCommand(), $packages, $dev);
    }

    public function remove(string|array $packages, bool $dev = false): int
    {
        return $this->run(new RemoveCommand(), $packages, $dev);
    }

    private function run(BaseCommand $command, string|array $packages, bool $dev): int
    {
        $command->setComposer($this->composer);
        $command->setApplication($this->application);

        $input = new ArrayInput(['packages' => (array) $packages]);

        if ($dev) {
            $input->setOption('dev', true);
        }

        return $command->run($input, $this->output);
    }
}
