<?php

namespace LaravelLangSyncInertia\Support;

use Illuminate\Support\Facades\File;

class GeneratedLangJsonLoader
{
    use NestsTranslationPaths;

    /**
     * Load generated JSON translation files for the given locale.
     *
     * @return array<string, mixed>
     */
    public function load(string $locale): array
    {
        $basePath = rtrim(
            (string) config('inertia-lang.output_lang'),
            DIRECTORY_SEPARATOR
        );
        $localePath = $basePath.DIRECTORY_SEPARATOR.$locale;

        if (! File::isDirectory($localePath)) {
            return [];
        }

        $tree = [];

        foreach (File::allFiles($localePath) as $file) {
            if ($file->getExtension() !== 'json') {
                continue;
            }

            $decoded = json_decode($file->getContents(), true);

            if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
                continue;
            }

            $relative = substr($file->getRelativePathname(), 0, -5);
            $segments = $this->segmentsFromReference($relative);

            $tree = array_replace_recursive(
                $tree,
                $this->nestBySegments($segments, $decoded)
            );
        }

        return $tree;
    }
}
