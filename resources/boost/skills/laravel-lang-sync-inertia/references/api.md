# API Reference

Use this file when the task is about the package API surface: helper signatures, facade methods, config keys, commands, or the Inertia shared prop contract.

## Global Helper

### `syncLangFiles()`

```php
syncLangFiles(string|array $fileName): array
```

Loads one or more Laravel translation groups for the current locale and stores them in the package loader so they can be shared with Inertia.

Examples:

```php
syncLangFiles('auth');
syncLangFiles(['auth', 'validation', 'pagination']);
```

Nested translation groups are supported. Reference a nested file using dot
(`admin.users`) or slash (`admin/users`) notation. The group is loaded from
`lang/{locale}/admin/users.php` and nested to mirror the reference:

```php
syncLangFiles('admin.users');
syncLangFiles(['admin.users', 'admin/roles']);
```

Return shape:

```php
[
    'auth' => [
        'failed' => 'These credentials do not match our records.',
    ],
    'admin' => [
        'users' => [
            'name' => 'Name',
        ],
    ],
]
```

Nested groups resolve on the frontend with the full key path, e.g.
`__('admin.users.name')`.

## Facade API

The package alias `Lang` maps to `LaravelLangSyncInertia\Facades\Lang`, which resolves `LaravelLangSyncInertia\Support\TranslationLoader`.

Documented methods:

### `Lang::load()`

```php
Lang::load(string $file): array
```

Loads one translation group for the current locale.

### `Lang::getFile()`

```php
Lang::getFile(string|array $file): array
```

Loads one or many translation groups and returns the merged, nested
translation tree for everything loaded so far in the request.

### `Lang::getLoaded()`

```php
Lang::getLoaded(): array
```

Returns all groups already loaded in the current request.

## Translation Loader Contract

`TranslationLoader` reads group files from:

```php
config('inertia-lang.lang_path', lang_path()) . '/' . app()->getLocale() . '/{group}.php'
```

`{group}` may be nested. A reference like `admin.users` (or `admin/users`)
reads `lang/{locale}/admin/users.php`.

Behavior:

- caches loaded groups in memory for the current request
- returns `[]` for missing group files
- supports one group or many groups
- supports nested groups via dot or slash notation, nesting the result to
  mirror the reference (`admin.users` => `['admin' => ['users' => [...]]]`)

## Shared Inertia Prop

The package shares translations under:

```php
page.props.lang
```

Typical shape:

```php
[
    'lang' => [
        'auth' => [
            'greeting' => 'Hello!',
            'welcome' => 'Welcome, :name',
        ],
        'validation' => [
            'required' => 'The :attribute field is required.',
        ],
    ],
]
```

Frontend code should treat `lang` as the translation root for helper lookups like `__('auth.greeting')`.

## Frontend Helper API

The companion frontend package exposes dedicated framework entrypoints:

Use these helpers directly inside the Vue, React, or Svelte page/component that needs translations. Do not configure this package in Vite, `app.ts`, or `app.js`.

### Vue

```ts
import { lang } from '@erag/lang-sync-inertia/vue';
```

### React

```tsx
import { lang } from '@erag/lang-sync-inertia/react';
```

### Svelte

```ts
import { lang } from '@erag/lang-sync-inertia/svelte';
```

Requires `@inertiajs/svelte` v3 (Svelte 5). The `page` reactive state from `@inertiajs/svelte` is read at call time, so helpers work correctly inside `$derived` expressions and reactive contexts.

The `lang()` helper returns methods including:

- `__()`
- `trans()`
- `transChoice()`
- `trans_choice()`

Typical usage:

```ts
const { __, trans, transChoice, trans_choice } = lang();
```

### `__()`

Direct translation lookup with optional replacements.

```ts
__('auth.greeting');
__('auth.welcome', { name: 'Amit' });
```

### `trans()`

Explicit translation lookup for strings that use replacements.

```ts
trans('auth.welcome', { name: 'Amit' });
```

### `transChoice()` / `trans_choice()`

Pluralization helpers.

```ts
transChoice('auth.notifications', 3);
trans_choice('auth.notifications', 0);
```

## Commands

### `php artisan erag:install-lang`

Publishes package config using the `erag:publish-lang-config` tag.

Expected use:

```bash
php artisan erag:install-lang
```

### `php artisan erag:generate-lang`

Generates JSON files from PHP translation groups into the configured output directory.
After generation, the package auto-loads all generated JSON translation files for the current locale from the configured output directory and merges them into the shared Inertia `lang` prop on each request.

Expected use:

```bash
php artisan erag:generate-lang
```

Output location:

```text
{output_lang}/{locale}/{group}.json
```

Nested translation directories are scanned recursively and the output folder
tree mirrors the source, e.g. `lang/{locale}/admin/users.php` generates
`{output_lang}/{locale}/admin/users.json`.

## Configuration API

Published config file:

```php
config/inertia-lang.php
```

Supported keys:

### `lang_path`

```php
'lang_path' => base_path('lang')
```

Base directory used to read PHP translation groups.

### `output_lang`

```php
'output_lang' => resource_path('js/lang')
```

Base directory used by `erag:generate-lang` and by the service provider when loading generated JSON translations.

## Merge Contract

At runtime, the service provider merges generated JSON translations with runtime-loaded groups like this:

```php
array_replace_recursive($jsonLang, $runtimeLang)
```

Effect:

- generated JSON is the base dataset
- `syncLangFiles()` runtime data overrides conflicting keys

## Locale Contract

The package uses:

```php
app()->getLocale()
```

That locale controls both:

- PHP translation group loading
- generated JSON locale directory loading
