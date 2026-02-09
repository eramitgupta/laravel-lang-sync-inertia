<?php

use LaravelLangSyncInertia\Facades\Lang;

if (! function_exists('syncLangFiles')) {
    /**
     * Load one or multiple language files.
     *
     * @return array<string, mixed>
     */
    function syncLangFiles(string|array $fileName): array
    {
        return Lang::getFile($fileName);
    }
}
