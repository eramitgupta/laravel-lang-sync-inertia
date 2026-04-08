---
title: Installation
description: Install Laravel Lang Sync Inertia for Laravel backend and Vue or React frontend apps.
head:
  - ['meta', { name: 'keywords', content: 'Laravel Lang Sync Inertia installation, composer require erag/laravel-lang-sync-inertia, npm install @erag/lang-sync-inertia' }]
---

# Installation

Install the package in two parts:

- `erag/laravel-lang-sync-inertia` for the Laravel backend
- `@erag/lang-sync-inertia` for Vue or React frontend usage

## Backend package

::: code-group

```bash [Laravel package]
composer require erag/laravel-lang-sync-inertia
```

```bash [Vue/React package]
npm install @erag/lang-sync-inertia
```

:::

## Publish language files

If your app does not already have published language files, publish them first:

```bash
php artisan lang:publish
```

Then publish the package assets and config:

```bash
php artisan erag:install-lang
```

## After installation

Once installation is complete:

1. Create or update your language files in `lang/{locale}`.
2. Call `syncLangFiles()` before returning your Inertia response.
3. Use `lang()` in Vue or React to read translations on the frontend.
