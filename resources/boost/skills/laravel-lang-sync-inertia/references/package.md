# Package Reference

This reference is for package-specific details that do not need to live in `SKILL.md`.

## Main Entry Points

- `src/LangSyncInertiaServiceProvider.php`
- `src/Support/TranslationLoader.php`
- `src/LangHelpers.php`
- `src/Middleware/ShareLangTranslations.php`
- `src/Facades/Lang.php`
- `config/inertia-lang.php`

## Package Surface

The package exposes:

- global helper: `syncLangFiles(string|array $fileName): array`
- facade: `Lang`
- middleware: `ShareLangTranslations`
- commands:
  - `php artisan erag:install-lang`
  - `php artisan erag:generate-lang`

## Service Provider Behavior

`LangSyncInertiaServiceProvider`:

- merges `config/inertia-lang.php` config namespace
- registers `InstallLangCommand` and `GenerateLangCommand`
- publishes `config/inertia-lang.php` with the `erag:publish-lang-config` tag
- requires `src/LangHelpers.php`
- registers the `Lang` alias
- pushes `ShareLangTranslations` onto the HTTP kernel
- shares `lang` with Inertia through `Inertia::share(...)`
- loads generated JSON translations from `config('inertia-lang.output_lang')/{locale}/*.json`
- merges generated JSON with runtime-loaded groups using `array_replace_recursive`

### Merge Order

The provider returns:

```php
$jsonLang
    ? array_replace_recursive($jsonLang, $runtimeLang)
    : $runtimeLang;
```

That means runtime-loaded translations from `syncLangFiles()` override generated JSON values for the same nested key.

## Translation Loader

`TranslationLoader`:

- tracks loaded groups in the `$loaded` property for the request lifecycle
- reads files from:

```php
config('inertia-lang.lang_path', lang_path()) . '/' . app()->getLocale() . '/{group}.php'
```

- returns an empty array when a group file does not exist
- supports loading a single group or an array of groups

### Facade Methods

The `Lang` facade maps to `TranslationLoader` and documents these methods:

- `load(string $file): array`
- `getFile(string|array $file): array`
- `getLoaded(): array`

Use `syncLangFiles()` or `Lang::getFile(...)` instead of manually requiring language files in controllers.

## Shared Inertia Data

The package shares translations under:

```php
page.props.lang
```

Runtime-loaded groups are shaped like:

```php
[
    'auth' => [
        'greeting' => 'Hello',
    ],
    'messages' => [
        'welcome' => 'Welcome, :name',
    ],
]
```

Generated JSON files are loaded per locale and keyed by the JSON filename:

```text
resources/js/lang/en/auth.json      -> lang.auth
resources/js/lang/en/messages.json  -> lang.messages
```

If the app runs `php artisan erag:generate-lang`, the package will automatically load all generated JSON translation files for the current locale from the configured `output_lang` directory.

That means no extra app code is needed to read those generated files, and `syncLangFiles()` is not required for the translation groups that already exist as generated JSON.

## Commands

### `php artisan erag:install-lang`

This command publishes package config by calling:

```php
php artisan vendor:publish --tag=erag:publish-lang-config --force
```

Use it after installation so the host app can change `lang_path` or `output_lang`.

### `php artisan erag:generate-lang`

This command:

- reads locale directories under `config('inertia-lang.lang_path')`
- skips non-PHP files
- requires each `*.php` language group
- writes JSON output to `config('inertia-lang.output_lang')/{locale}/{group}.json`
- skips files whose return value is not an array

Generated JSON is pretty-printed and uses `JSON_UNESCAPED_UNICODE`.

After this command has generated the JSON files, `LangSyncInertiaServiceProvider` automatically reads all generated files for the current locale on each request and merges them into the shared Inertia `lang` prop.

Use `syncLangFiles()` only when the app needs runtime-loaded groups in addition to, or instead of, the generated JSON files.

## Frontend Expectations

The package is designed for the companion NPM package:

- `@erag/lang-sync-inertia/vue`
- `@erag/lang-sync-inertia/react`
- `@erag/lang-sync-inertia/svelte`

Frontend helpers read from the shared `lang` prop and expose:

- `__()` for direct lookup
- `trans()` for replacement-heavy lookups
- `transChoice()` and `trans_choice()` for pluralization

Use the helper directly in each page or component that needs translations. Do not configure the helper in Vite, `app.ts`, or `app.js`, and do not register it as an Inertia app plugin/provider. For Svelte, requires `@inertiajs/svelte` v3 (Svelte 5).

## Configuration

Published config keys:

```php
return [
    'lang_path' => base_path('lang'),
    'output_lang' => resource_path('js/lang'),
];
```

Guidance:

- Use `lang_path` when the app stores PHP translations outside the default Laravel `lang` directory.
- Use `output_lang` when build tooling or frontend code expects generated JSON in a custom location.

## Integration Checklist

For a typical host app:

1. Install the Composer package.
2. Install `@erag/lang-sync-inertia`.
3. Publish Laravel language files if needed.
4. Run `php artisan erag:install-lang`.
5. Call `syncLangFiles()` in each Inertia controller action that needs translations.
6. Import `lang()` directly inside each Vue, React, or Svelte page/component that needs translations.
7. Optionally run `php artisan erag:generate-lang` for build-time JSON output.
