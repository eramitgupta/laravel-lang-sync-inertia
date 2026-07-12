<?php

namespace LaravelLangSyncInertia\Support;

class TranslationLoader
{
    use NestsTranslationPaths;

    /**
     * Per-reference cache keyed by the original reference string.
     *
     * @var array<string, array<string, mixed>>
     */
    protected array $cache = [];

    /**
     * Merged, nested translation tree for every reference loaded so far.
     *
     * @var array<string, mixed>
     */
    protected array $tree = [];

    /**
     * Load a single translation group.
     *
     * The reference may point at a nested group using dot ("admin.users")
     * or slash ("admin/users") notation. The returned array is nested to
     * mirror the reference, so "admin.users" resolves to
     * ['admin' => ['users' => [...]]].
     *
     * @return array<string, mixed>
     */
    public function load(string $file): array
    {
        if (isset($this->cache[$file])) {
            return $this->cache[$file];
        }

        $lang = app()->getLocale();
        $basePath = rtrim(config('inertia-lang.lang_path', lang_path()), '/');

        $segments = $this->segmentsFromReference($file);
        $relative = implode(DIRECTORY_SEPARATOR, $segments);
        $path = "{$basePath}/{$lang}/{$relative}.php";

        $contents = file_exists($path) ? require $path : [];
        $contents = is_array($contents) ? $contents : [];

        $nested = $this->nestBySegments($segments, $contents);

        $this->cache[$file] = $nested;
        $this->tree = array_replace_recursive($this->tree, $nested);

        return $nested;
    }

    /**
     * Load one or multiple translation groups.
     *
     * @param  string|array<int, string>  $files
     * @return array<string, mixed>
     */
    public function getFile(string|array $files): array
    {
        $files = (array) $files;

        foreach ($files as $file) {
            $this->load($file);
        }

        return $this->tree;
    }

    /**
     * @return array<string, mixed>
     */
    public function getLoaded(): array
    {
        return $this->tree;
    }
}
