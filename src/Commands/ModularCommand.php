<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;

class ModularCommand extends Command
{
    public $signature = 'modular';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
