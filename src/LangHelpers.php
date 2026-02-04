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


/**
 * WRITE generated files to resources/js/lang
 */
if (! function_exists('outputPathLang')) {
    function outputPathLang(): string
    {
        return resource_path('js/lang');
    }
}
