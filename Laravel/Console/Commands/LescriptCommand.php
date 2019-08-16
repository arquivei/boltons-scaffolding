<?php

namespace Arquivei\BoltonsScaffolding\Laravel\Console\Commands;

use Arquivei\BoltonsScaffolding\Lescript;
use Illuminate\Console\Command;

class LescriptCommand extends Command
{
    protected $signature = 'arquivei:lescript {--config=}';

    public function handle()
    {
        $configPath = $this->option('config');

        $lescript = new Lescript($configPath);
        $lescript->makeLeMagique();
    }
}
