<?php

namespace LaravelLangSyncInertia\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class InstallLangCommand extends Command
{
    protected $signature = 'erag:install-lang';

    protected $description = '📦 Publish language configuration and initialize LaravelLangSyncInertia.';

    public function handle()
    {
        $this->info('🔧 Publishing language configuration...');
        $this->call('vendor:publish', [
            '--tag' => 'erag:publish-lang-config',
            '--force' => true,
        ]);
        $this->info('✅ Configuration published successfully.');
        $this->newLine();

        $this->info('📦 Installing frontend helper via NPM...');

        $result = Process::run('npm install @erag/lang-sync-inertia');

        if ($result->successful()) {
            $this->info('✅ NPM package installed successfully.');
        } else {
            $this->error('❌ Failed to install NPM package.');
            $this->line($result->errorOutput());
        }

    }
}
