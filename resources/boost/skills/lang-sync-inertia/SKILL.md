---
name: lang-sync-inertia
description: >-
  Supports erag/laravel-lang-sync-inertia, a Laravel package that syncs backend
  language files to Inertia.js frontends and exposes shared translations for Vue
  and React usage. Activate when working with syncLangFiles(), Inertia shared
  lang props, generated frontend language JSON, service provider integration,
  translation helpers, locale-aware language loading, or backward-compatible
  frontend APIs such as lang(), vueLang(), and reactLang().
---

# Lang Sync Inertia

## When to Apply

Apply this skill when working on:
- syncing Laravel language files to Inertia frontends
- `syncLangFiles()` usage or implementation
- `Inertia::share('lang', ...)`
- frontend translation APIs for Vue or React
- generated JSON language export support
- locale-aware translation loading
- package installation, publishing, or commands
- backward-compatible API design for `lang()`, `vueLang()`, and `reactLang()`
- debugging missing or stale translation data

## Package Purpose

This package bridges Laravel backend translations with Inertia.js frontends.

It currently:
- provides a global helper `syncLangFiles(string|array $fileName): array`
- loads translation data through the package `Lang` facade
- shares translations to the frontend through `Inertia::share('lang', ...)`
- merges runtime-loaded translations with generated JSON translations
- resolves generated JSON per current Laravel locale
- supports frontend consumption through the companion NPM package
- supports Vue and React consumers

## Current Architecture

### Helper
- `syncLangFiles()` is defined in `src/LangHelpers.php`
- it delegates to `Lang::getFile($fileName)`

### Service provider
`LangSyncInertiaServiceProvider` is responsible for:
- merging config from `config/inertia-lang.php`
- registering package commands
- publishing config
- requiring helpers
- registering alias `Lang`
- pushing middleware `ShareLangTranslations`
- sharing `lang` data with Inertia

### Shared translation flow
The package shares translations with:

```php
Inertia::share('lang', function () {
    $locale = app()->getLocale();

    $runtimeLang = Lang::getLoaded();
    $jsonLang = $this->loadGeneratedLangJson($locale);

    return $jsonLang
        ? array_replace_recursive($jsonLang, $runtimeLang)
        : $runtimeLang;
});