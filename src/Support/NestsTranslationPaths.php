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
        if (str_contains($reference, '/') || str_contains($reference, '\\')) {
            return [];
        }

        return array_values(array_filter(
            preg_split('#[.]+#', $reference) ?: [],
            static fn ($segment) => $segment !== ''
        ));
    }

    /**
     * Split a generated language file path into its directory segments.
     *
     * @return array<int, string>
     */
    protected function segmentsFromRelativePath(string $path): array
    {
        return array_values(array_filter(
            preg_split('#[/\\\\]+#', $path) ?: [],
            static fn ($segment) => $segment !== ''
        ));
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
