---
name: laravel-lang-sync-inertia
description: "Activate when the user is adding, debugging, or documenting Laravel to Inertia translation syncing with erag/laravel-lang-sync-inertia. Use for syncLangFiles(), Lang facade usage, shared Inertia lang props, generated frontend JSON translations, config/inertia-lang.php, package install or generate commands, Vue or React frontend lang() helpers, pluralization helpers, locale-aware loading, or backward-compatible APIs like vueLang() and reactLang()."
license: MIT
metadata:
  author: laravel
---

# Laravel Lang Sync Inertia

Use this skill when a Laravel task involves `erag/laravel-lang-sync-inertia`.

## Documentation

Use `search-docs` first when it is available for Laravel and Inertia integration details. For package-specific behavior, inspect:

- `src/LangSyncInertiaServiceProvider.php`
- `src/Support/TranslationLoader.php`
- `src/LangHelpers.php`
- `src/Middleware/ShareLangTranslations.php`
- `src/Commands/InstallLangCommand.php`
- `src/Commands/GenerateLangCommand.php`
- `config/inertia-lang.php`
- `references/api.md`
- `references/package.md`
- `references/examples.md`

Load only the reference file you need:

- `references/api.md` for helper, facade, config, command, and shared-prop API details
- `references/package.md` for package surface and behavior rules
- `references/examples.md` for Laravel, Vue, React, and JSON export examples

## Core Working Pattern

1. Install the backend package and the frontend helper package.
2. Publish Laravel language files with `php artisan lang:publish` when the app does not already have them.
3. Publish the package config with `php artisan erag:install-lang`.
4. Choose one loading strategy:
   - call `syncLangFiles()` inside the controller action before returning `Inertia::render(...)`, or
   - run `php artisan erag:generate-lang` to generate JSON translation files for the configured output path
5. Read translations on the frontend with `lang()` from `@erag/lang-sync-inertia/vue` or `@erag/lang-sync-inertia/react`.

## Important Behavior

- `syncLangFiles(string|array $fileName)` loads one or many Laravel translation groups for the current locale.
- The package shares translations under the `lang` Inertia prop.
- `TranslationLoader` reads from `config('inertia-lang.lang_path')` and caches loaded groups in memory for the request.
- `LangSyncInertiaServiceProvider` automatically loads generated JSON files from `config('inertia-lang.output_lang')/{locale}/*.json`.
- After `php artisan erag:generate-lang`, you do not add any extra app code for JSON loading. The package reads all generated JSON files for the current locale during the normal Inertia share flow in `LangSyncInertiaServiceProvider`.
- When generated JSON files are present, the package auto-loads all generated translation groups for the current locale. You do not need to call `syncLangFiles()` for those generated groups.
- Generated JSON translations are merged first and runtime-loaded translations win on conflicts through `array_replace_recursive`.
- The current locale comes from `app()->getLocale()`.
- Vue and React consumers should prefer the dedicated entrypoints:
  - `@erag/lang-sync-inertia/vue`
  - `@erag/lang-sync-inertia/react`
- The package keeps legacy compatibility helpers available through the root package import:
  - `vueLang()`
  - `reactLang()`
- Frontend helpers support:
  - `__()`
  - `trans()`
  - `transChoice()`
  - `trans_choice()`

## Configuration

Key config values in `config/inertia-lang.php`:

- `lang_path`: where backend PHP translation files are read from
- `output_lang`: where `erag:generate-lang` writes frontend JSON output

Prefer changing these config values instead of rewriting the package loader behavior.

## Verification

For package work, validate the affected flow directly:

- installation and publish flow
- controller-side `syncLangFiles()` usage
- locale-aware loading from the configured language directory
- generated JSON merge behavior
- frontend helper usage in Vue or React

When editing the package itself, also review the generated diff for `resources/boost/skills/lang-sync-inertia/*` so the skill stays concise and references stay load-on-demand.

## Common Pitfalls

- Calling `syncLangFiles()` after returning the Inertia response
- Expecting translations to appear in Blade views instead of Inertia props
- Forgetting `php artisan lang:publish` in apps that do not yet have `lang/{locale}` files
- Importing from the root frontend package for new code instead of `/vue` or `/react`
- Changing config paths without updating deployment or build steps that rely on generated JSON
