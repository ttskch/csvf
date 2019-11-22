<?php

declare(strict_types=1);

namespace Ttskch\Csvf\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WithCommand extends Command
{
    public function configure() : void
    {
        $this
            ->setName('with')
            ->setDescription('Formats CSV with given parameters');
    }

    public function execute(InputInterface $input, OutputInterface $output) : void
    {
    }
}
