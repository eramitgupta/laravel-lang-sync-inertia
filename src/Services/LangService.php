<?php

namespace LaravelLangSyncInertia\Services;

class LangService
{
    protected array $loaded = [];

    public function load(string $file): array
    {
        if (isset($this->loaded[$file])) {
            return $this->loaded[$file];
        }

        $lang = app()->getLocale();
        $basePath = rtrim(config('inertia-lang.lang_path', lang_path()), '/');
        $path = "{$basePath}/{$lang}/{$file}.php";

        $this->loaded[$file] = file_exists($path) ? require $path : [];

        return $this->loaded[$file];
    }

    public function getFile(string|array $files): array
    {
        $files = (array) $files;

        return collect($files)->mapWithKeys(function ($file) {
            return [$file => $this->load($file)];
        })->all();
    }

    public function getLoaded(): array
    {
        return $this->loaded;
    }
}
