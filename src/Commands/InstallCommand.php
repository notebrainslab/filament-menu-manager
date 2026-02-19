<?php

namespace SolutionForest\FilamentMenuManager\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    public $signature = 'filament-menu-manager:install';
    public $description = 'Install Filament Menu Manager: publish config, migrations, and run migrations.';

    public function handle(): int
    {
        $this->info('📦 Installing Filament Menu Manager...');

        $this->call('vendor:publish', [
            '--tag'    => 'filament-menu-manager-migrations',
            '--force'  => false,
        ]);

        $this->call('vendor:publish', [
            '--tag'    => 'filament-menu-manager-config',
            '--force'  => false,
        ]);

        if ($this->confirm('Run migrations now?', true)) {
            $this->call('migrate');
        }

        $this->newLine();
        $this->info('✅ Filament Menu Manager installed!');
        $this->line('');
        $this->line('Next step — register the plugin in your Panel Provider:');
        $this->line('');
        $this->line("  ->plugin(FilamentMenuManagerPlugin::make()");
        $this->line("      ->locations(['primary' => 'Primary Navigation'])");
        $this->line("  )");
        $this->newLine();

        return self::SUCCESS;
    }
}
