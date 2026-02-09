<?php

namespace LaravelLangSyncInertia;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use LaravelLangSyncInertia\Commands\GenerateLangCommand;
use LaravelLangSyncInertia\Commands\InstallLangCommand;
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
            InstallLangCommand::class,
            GenerateLangCommand::class,
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
        Inertia::share('pageLang', function () {
            $locale = app()->getLocale();

            $runtimeLang = Lang::getLoaded();

            $jsonLang = $this->loadGeneratedLangJson($locale);

            return [
                'lang' => $jsonLang
                    ? array_replace_recursive($jsonLang, $runtimeLang)
                    : $runtimeLang,

                'locale' => $locale,
            ];
        });
    }

    private function loadGeneratedLangJson(string $locale): array
    {
        $basePath = rtrim(
            (string) config('inertia-lang.output_lang'),
            DIRECTORY_SEPARATOR
        );
        $localePath = $basePath.DIRECTORY_SEPARATOR.$locale;

        if (! File::isDirectory($localePath)) {
            return [];
        }

        $jsonFiles = File::glob($localePath.DIRECTORY_SEPARATOR.'*.json');

        return collect($jsonFiles)
            ->mapWithKeys(function ($file) {
                $key = pathinfo($file, PATHINFO_FILENAME);
                $content = File::get($file);
                $decoded = json_decode($content, true);

                return (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
                    ? [$key => $decoded]
                    : [];
            })
            ->all();
    }
}
