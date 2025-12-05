<?php

namespace LaravelLangSyncInertia;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use LaravelLangSyncInertia\Commands\InstallLang;
use LaravelLangSyncInertia\Facades\Lang;
use LaravelLangSyncInertia\Middleware\ShareLangTranslations;

class LangSyncInertiaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfig();
        $this->registerCommands();
        $this->publishConfig();
    }

    public function boot(): void
    {
        $this->loadHelpers();
        $this->registerAlias();
        $this->registerMiddleware();
        $this->shareLangWithInertia();
    }

    protected function mergeConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/inertia-lang.php',
            'lang-manager'
        );
    }

    protected function registerCommands(): void
    {
        $this->commands([
            InstallLang::class,
        ]);
    }

    protected function publishConfig(): void
    {
        $this->publishes([
            __DIR__.'/../config/inertia-lang.php' => config_path('inertia-lang.php'),
        ], 'erag:publish-lang-config');

    }

    protected function loadHelpers(): void
    {
        $helpers = __DIR__.'/LangHelpers.php';

        if (is_file($helpers)) {
            require_once $helpers;
        }
    }

    protected function registerAlias(): void
    {
        if (class_exists(AliasLoader::class)) {
            AliasLoader::getInstance()->alias('Lang', Lang::class);
        }
    }

    protected function registerMiddleware(): void
    {
        $this->app->make(Kernel::class)
            ->pushMiddleware(ShareLangTranslations::class);
    }

    protected function shareLangWithInertia(): void
    {
        Inertia::share('lang', fn () => Lang::getLoaded());
    }
}
