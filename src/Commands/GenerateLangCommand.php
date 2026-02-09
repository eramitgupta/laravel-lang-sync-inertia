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

        $this->info('🔍 Generating frontend language files...');

        foreach (File::directories($langBasePath) as $localeDir) {
            $locale = basename($localeDir);
            $localeExportPath = $exportLangPath.DIRECTORY_SEPARATOR.$locale;

            File::ensureDirectoryExists($localeExportPath);

            foreach (File::files($localeDir) as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $group = $file->getFilenameWithoutExtension();
                $content = require $file->getRealPath();

                if (! is_array($content)) {
                    continue;
                }

                File::put(
                    $localeExportPath.DIRECTORY_SEPARATOR."{$group}.json",
                    json_encode(
                        $content,
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                    )
                );
            }
        }

        $this->info('✅ Language JSON files generated successfully!');
        $this->line("→ {$exportLangPath}/{locale}/{group}.json");

        return self::SUCCESS;
    }
}
