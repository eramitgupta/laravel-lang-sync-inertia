<?php

namespace LaravelLangSyncInertia\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateLangCommand extends Command
{
    protected $signature = 'erag:generate-lang';

    protected $description = 'Generate all language keys for frontend';

    public function handle(): int
    {
        $baseLangPath = (string) config('inertia-lang.lang_path');
        $exportPath = outputPathLang();

        if (! File::exists($baseLangPath)) {
            $this->error(sprintf('Language base path not found: %s', $baseLangPath));

            return self::FAILURE;
        }

        $this->info('🔍 Exporting all language keys...');

        $result = [];

        foreach (File::directories($baseLangPath) as $localePath) {
            $locale = basename($localePath);

            foreach (File::files($localePath) as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $content = require $file->getRealPath();

                if (! is_array($content)) {
                    continue;
                }

                $group = $file->getFilenameWithoutExtension();
                $result[$locale][$group] = array_keys($content);
            }
        }

        File::ensureDirectoryExists($exportPath);

        $this->exportJson($exportPath, $result);
        $this->exportTypes($exportPath, $result);

        $this->info('✅ All language keys exported successfully!');
        $this->newLine();
        $this->line(sprintf('→ %s/lang-keys.json', $exportPath));
        $this->line(sprintf('→ %s/lang-keys.d.ts', $exportPath));

        return self::SUCCESS;
    }

    /**
     * @param  array<string, array<string, array<int, string>>>  $data
     */
    private function exportJson(string $exportPath, array $data): void
    {
        File::put(
            $exportPath.'/lang-keys.json',
            json_encode(
                $data,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
        );
    }

    /**
     * @param  array<string, array<string, array<int, string>>>  $data
     */
    private function exportTypes(string $exportPath, array $data): void
    {
        $content = "// AUTO-GENERATED FILE — DO NOT EDIT\n\n";

        $content .= 'export type Locale = '
            .implode(
                ' | ',
                array_map(
                    static fn (string $locale): string => "'{$locale}'",
                    array_keys($data)
                )
            )
            .";\n\n";

        $content .= "export type LangKey =\n";

        foreach ($data as $locale => $groups) {
            foreach ($groups as $group => $keys) {
                foreach ($keys as $key) {
                    $content .= "  | '{$locale}.{$group}.{$key}'\n";
                }
            }
        }

        File::put($exportPath.'/lang-keys.d.ts', $content);
    }
}
