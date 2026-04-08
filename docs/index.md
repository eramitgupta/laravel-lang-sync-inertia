---
title: Laravel Lang Sync Inertia
description: Bridge Laravel translation files to Inertia.js apps with first-class Vue and React support.
head:
  - ['meta', { name: 'keywords', content: 'Laravel Lang Sync Inertia, Inertia.js translations, Laravel i18n, Vue translations, React translations, localization package' }]
layout: home

hero:
  name: "Laravel Lang Sync Inertia"
  text: "Laravel translations, instantly available in Inertia Vue and React apps"
  tagline: "Load backend language files once, access them anywhere on the frontend with clean helpers and placeholder support."
  image:
    src: /hero-orb.svg
    alt: Laravel Lang Sync Inertia

features:
  - icon: "◉"
    title: Laravel-native flow
    details: Call `syncLangFiles()` in your controller and let Inertia share translations automatically.
  - icon: "✦"
    title: Vue and React support
    details: Use the same translation API in both Vue 3 and React applications with dedicated framework entry points.
  - icon: "⌘"
    title: Placeholder ready
    details: Resolve strings like `Welcome, {name}!` at runtime using `trans()` or `__()`.
  - icon: "◐"
    title: Locale aware
    details: The package reads `resources/lang/{locale}` based on Laravel's current app locale.
  - icon: "⤴"
    title: Export support
    details: Generate frontend-ready JSON files from PHP lang files when you need a static output.
  - icon: "☷"
    title: Minimal API surface
    details: Learn one simple API and use it consistently across backend and frontend.
---

## Quick example

The backend decides which language files should be available for the page.
After that, your frontend can read translations with a very small API.

- Call `syncLangFiles()` in the controller.
- Use `lang()` in Vue or React.
- Render translations with `__()` or `trans()`.

::: code-group

```php [Controller]
<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        syncLangFiles(['auth', 'dashboard']);

        return Inertia::render('Dashboard');
    }
}
```

```vue [Vue]
<script setup>
import { lang } from '@erag/lang-sync-inertia/vue'

const { __, trans } = lang()
</script>

<template>
  <h1>{{ __('auth.greeting') }}</h1>
  <p>{{ trans('auth.welcome', { name: 'Amit' }) }}</p>
</template>
```

```tsx [React]
import { lang } from '@erag/lang-sync-inertia/react'

export default function Dashboard() {
  const { __, trans } = lang()

  return (
    <section>
      <h1>{__('auth.greeting')}</h1>
      <p>{trans('auth.welcome', { name: 'Amit' })}</p>
    </section>
  )
}
```

:::

## Packages

Choose the package you want to install first.

::: code-group

```bash [Backend package]
composer require erag/laravel-lang-sync-inertia

php artisan lang:publish

php artisan erag:install-lang
```

```bash [Frontend package]
npm install @erag/lang-sync-inertia
```

:::

## Why use it

`Laravel Lang Sync Inertia` connects Laravel translation files to your Inertia frontend without manually passing props in every response.

This package is useful when:

- your translations already live in Laravel lang files
- your frontend is built with Inertia.js
- you want the same translation flow in Vue and React
- you need placeholder replacement without custom glue code
