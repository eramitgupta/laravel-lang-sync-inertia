---
title: Introduction
description: Introduction to Laravel Lang Sync Inertia for sharing Laravel translations with Inertia.js Vue and React apps.
head:
  - ['meta', { name: 'keywords', content: 'Laravel Lang Sync Inertia introduction, Inertia.js translation package, Laravel localization, Vue and React translations' }]
---

# Introduction

`Laravel Lang Sync Inertia` is a Laravel package that bridges your backend translation files with your Inertia.js frontend. Load any language file in a controller and access it instantly in Vue or React without manually passing props.

It allows Laravel language files to be shared automatically through Inertia, so your frontend can use translations with a small API instead of manually wiring props on every response.

This is useful when your project already stores translations in Laravel language files and you want the same source of truth across backend and frontend screens. Instead of duplicating translation logic, the package lets Laravel stay in control while Inertia delivers the shared data to your client-side pages.

The overall goal is simple: keep your translation workflow native to Laravel, but make the translated content immediately available inside your Vue or React components.

## Features

- Automatic translation sharing via Inertia.js middleware
- Load single or multiple language files with one call
- Dynamic placeholder replacement with `{name}` syntax
- Works with both Vue 3 and React
- Auto-switches locale folder based on Laravel's current locale
- Export PHP language files to frontend-ready JSON
- Clean helper API with `trans()` and `__()`

## How It Works

When you call `syncLangFiles('auth')` in your controller:

1. Laravel reads `resources/lang/{locale}/auth.php` based on `App::getLocale()`.
2. The translation array is shared with Inertia under `page.props.lang`.
3. Your frontend helper reads from `page.props.lang`.
4. Keys like `auth.greeting` resolve automatically.
5. Placeholders like `{name}` are replaced at runtime with the values you pass.

```text
Controller -> syncLangFiles('auth')
Laravel reads -> resources/lang/en/auth.php
Inertia shares -> page.props.lang.auth
Frontend -> __('auth.greeting') or trans('auth.welcome', { name: 'Amit' })
Output -> Hello! / Welcome, Amit!
```

Supported use cases:

- single or multiple language file loading
- Vue 3 and React apps
- placeholder replacement with `{name}` values
- locale-based loading from Laravel's current app locale
- optional JSON export for frontend-ready files
