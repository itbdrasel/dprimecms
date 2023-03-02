<?php

namespace Sourcebit\Dprimecms\Console;

use Illuminate\Console\Command;

class InstallDprimeCmsPackage extends Command
{
    protected $signature = 'dprimecms:install';

    protected $description = 'Install the DprimeCMS';

    public function handle() {
        $this->info('Installing DprimeCMS...');

        $this->info('Publishing configuration...');

        $this->call('vendor:publish', [
            '--provider' => "Sourcebit\Dprimecms\Providers\DependencyProviders",
            '--tag' => "config"
        ]);
        $this->call('vendor:publish', [
            '--provider' => "Sourcebit\Dprimecms\Providers\DependencyProviders",
            '--tag' => "public"
        ]);

        $this->info('Installed DprimeCMS');
    }

}
