<?php

namespace NoteBrainsLab\FilamentMenuManager\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'filament-menu-manager:install';

    protected $description = 'Install Filament Menu Manager with optional publishing and migrations.';

    public function handle(): int
    {
        $this->info('Installing Filament Menu Manager...');
        $this->newLine();

        /*
         |----------------------------------------------------------
         | Optional: Publish Config
         |----------------------------------------------------------
         */
        if ($this->confirm('Publish configuration file?', true)) {
            $this->info('Publishing configuration...');
            $this->call('vendor:publish', [
                '--tag'   => 'filament-menu-manager-config',
                '--force' => false,
            ]);
            $this->newLine();
        }

        /*
         |----------------------------------------------------------
         | Optional: Publish Migrations
         |----------------------------------------------------------
         */
        if ($this->confirm('Publish migrations?', true)) {
            $this->info('Publishing migrations...');
            $this->call('vendor:publish', [
                '--tag'   => 'filament-menu-manager-migrations',
                '--force' => false,
            ]);
            $this->newLine();
        }

        /*
         |----------------------------------------------------------
         | Optional: Publish Views
         |----------------------------------------------------------
         */
        if ($this->confirm('Publish views for customization?', false)) {
            $this->info('Publishing views...');
            $this->call('vendor:publish', [
                '--tag'   => 'filament-menu-manager-views',
                '--force' => false,
            ]);
            $this->newLine();
        }

        /*
         |----------------------------------------------------------
         | Optional: Run Migrations
         |----------------------------------------------------------
         */
        if ($this->confirm('Run migrations now?', true)) {
            $this->call('migrate');
            $this->newLine();
        }

        $this->info('Filament Menu Manager installed successfully!');
        $this->newLine();

        $this->line('Next step — register the plugin in your Panel Provider:');
        $this->newLine();

        $this->line("  ->plugin(FilamentMenuManagerPlugin::make()");
        $this->line("      ->locations(['primary' => 'Primary Navigation'])");
        $this->line("  )");

        $this->newLine();

        return self::SUCCESS;
    }
}