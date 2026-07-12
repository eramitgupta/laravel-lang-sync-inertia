<?php

declare(strict_types=1);

namespace LaravelLangSyncInertia\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class GenerateLangCommand extends Command
{
    protected $signature = 'erag:generate-lang';

    protected $description = 'Generate frontend language JSON files';

    public function handle(): int
    {
        $langBasePath = (string) config('inertia-lang.lang_path');
        $exportLangPath = (string) config('inertia-lang.output_lang');

        if (! File::exists($langBasePath)) {
            $this->error(sprintf(
                'Language base path not found: %s',
                $langBasePath
            ));

            return self::FAILURE;
        }

        File::ensureDirectoryExists($exportLangPath);

        $this->info('Generating language files...');

        foreach (File::directories($langBasePath) as $localeDir) {
            $locale = basename($localeDir);
            $localeExportPath = $exportLangPath.DIRECTORY_SEPARATOR.$locale;

            File::ensureDirectoryExists($localeExportPath);

            foreach (File::allFiles($localeDir) as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $content = require $file->getRealPath();

                if (! is_array($content)) {
                    continue;
                }

                $relativeJson = preg_replace(
                    '/\.php$/',
                    '.json',
                    $file->getRelativePathname()
                );

                $targetPath = $localeExportPath.DIRECTORY_SEPARATOR.$relativeJson;

                File::ensureDirectoryExists(dirname($targetPath));

                File::put(
                    $targetPath,
                    json_encode(
                        $content,
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                    )
                );
            }
        }

        $this->info('Language files generated successfully.');
        $this->line("Generated files are available in: {$exportLangPath}/{locale}/{group}.json");

        return self::SUCCESS;
    }
}
