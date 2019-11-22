<?php

declare(strict_types=1);

namespace Ttskch\Csvf;

use Symfony\Component\Console\Application;
use Ttskch\Csvf\Command\WithCommand;

class Csvf
{
    public function run() : void
    {
        $console = new Application();
        $console->setName('party');
        $console->add($with = new WithCommand());
        $console->setDefaultCommand($with->getName());
        $console->run();
    }
}
