<?php

namespace LaravelLangSyncInertia\Facades;

use Illuminate\Support\Facades\Facade;
use LaravelLangSyncInertia\Support\TranslationLoader;

class Lang extends Facade
{
    /**
     * @method static array load(string $file)
     * @method static array getFile(string $file)
     * @method static array getLoaded()
     */
    protected static function getFacadeAccessor(): string
    {
        return TranslationLoader::class;
    }
}
