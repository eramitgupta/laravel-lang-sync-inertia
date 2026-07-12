<?php

namespace LaravelLangSyncInertia\Support;

trait NestsTranslationPaths
{
    /**
     * Split a translation reference into its path segments.
     *
     * Accepts dot ("admin.users") notation and discards empty segments so
     * leading/trailing separators are ignored.
     *
     * @return array<int, string>
     */
    protected function segmentsFromReference(string $reference): array
    {
        if (! preg_match('/^[A-Za-z0-9_-]+(?:\.[A-Za-z0-9_-]+)*$/', $reference)) {
            return [];
        }

        return explode('.', $reference);
    }

    /**
     * Wrap a value inside a nested array following the given segments.
     *
     * ['admin', 'users'] + [...] => ['admin' => ['users' => [...]]]
     *
     * @param  array<int, string>  $segments
     * @param  array<string, mixed>  $value
     * @return array<string, mixed>
     */
    protected function nestBySegments(array $segments, array $value): array
    {
        foreach (array_reverse($segments) as $segment) {
            $value = [$segment => $value];
        }

        return $value;
    }
}
