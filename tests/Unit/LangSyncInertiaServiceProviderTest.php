<?php

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use LaravelLangSyncInertia\LangSyncInertiaServiceProvider;

require_once dirname(__DIR__, 2).'/src/LangSyncInertiaServiceProvider.php';

it('merges the default generated language path into the package config namespace', function () {
    $app = new Application(sys_get_temp_dir().'/laravel-lang-sync-inertia-test');
    $app->instance('config', new Repository);

    $provider = new class($app) extends LangSyncInertiaServiceProvider
    {
        public function mergePackageConfig(): void
        {
            $this->mergeConfig();
        }
    };

    $provider->mergePackageConfig();

    expect($app->make('config')->get('inertia-lang.output_lang'))
        ->toBe($app->resourcePath('js/lang'));
});
