<?php

namespace Arquivei\BoltonsScaffolding\Laravel\Providers;

use Arquivei\BoltonsScaffolding\Laravel\Console\Commands\LescriptCommand;
use Illuminate\Support\ServiceProvider;

class LescriptProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->commands([
            LescriptCommand::class
        ]);
    }
}
