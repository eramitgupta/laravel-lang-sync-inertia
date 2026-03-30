<?php

namespace LaravelLangSyncInertia\Commands;

use Illuminate\Console\Command;

class InstallLangCommand extends Command
{
    protected $signature = 'erag:install-lang';

    protected $description = 'Publish language configuration and initialize LaravelLangSyncInertia.';

    public function handle()
    {
        $this->components->info('Publishing language configuration...');
        $this->call('vendor:publish', [
            '--tag' => 'erag:publish-lang-config',
            '--force' => true,
        ]);
        $this->components->info('Language configuration published successfully.');
        $this->newLine();
    }
}
