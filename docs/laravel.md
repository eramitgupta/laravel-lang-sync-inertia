---
title: Laravel
description: Laravel-side usage of syncLangFiles() with controller examples and locale-based loading.
head:
  - ['meta', { name: 'keywords', content: 'Laravel Lang Sync Inertia Laravel guide, syncLangFiles Laravel, Inertia controller translations, Laravel localization example' }]
---

# Laravel

Use `syncLangFiles()` inside any controller action that returns an Inertia response.

`syncLangFiles()` is a global Laravel helper from this package. It reads your backend language files and shares them with Inertia automatically, so you do not have to pass translation props manually in every response.

Think of `syncLangFiles()` as a bridge between your Laravel language files and your Inertia responses.

## Single file

```php
syncLangFiles('auth');
```

## Multiple files

```php
syncLangFiles(['auth', 'validation', 'pagination']);
```

## Dashboard example

```php
syncLangFiles(['auth', 'dashboard']);

return Inertia::render('Dashboard');
```

## Full example

::: code-group

```php
<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        syncLangFiles('auth');

        return Inertia::render('Dashboard');
    }
}
```

```php [lang/en/auth.php]
return [
    'greeting' => 'Hello!',
    'welcome' => 'Welcome, {name}!',
];
```

```vue [js/Pages/Dashboard.vue]
<script setup>
import { lang } from '@erag/lang-sync-inertia/vue'

const { trans, __ } = lang()
</script>

<template>
  <section>
    <h1>{{ __('auth.greeting') }}</h1>
    <p>{{ trans('auth.welcome', { name: 'Amit' }) }}</p>
  </section>
</template>
```

```tsx [js/Pages/Dashboard.tsx]
import { lang } from '@erag/lang-sync-inertia/react'

export default function Dashboard() {
  const { trans, __ } = lang()

  return (
    <section>
      <h1>{__('auth.greeting')}</h1>
      <p>{trans('auth.welcome', { name: 'Amit' })}</p>
    </section>
  )
}
```

```text [Flow]
Controller -> syncLangFiles('auth')
Laravel -> resources/lang/{locale}/auth.php
Inertia -> page.props.lang.auth
Frontend -> __('auth.greeting')
Output -> Hello!
```

:::

This loads `resources/lang/{locale}/auth.php` based on Laravel's current locale and shares it with Inertia automatically.

## How it works

When you call `syncLangFiles('auth')`:

1. Laravel reads `resources/lang/{locale}/auth.php` based on `App::getLocale()`.
2. The package shares that data through Inertia props.
3. Your frontend helper reads from `page.props.lang`.
4. Keys like `auth.greeting` resolve automatically.

## Locale-based loading

The package uses Laravel's current locale, so this structure works out of the box:

```text
resources/lang/
├── en/
│   └── auth.php
└── hi/
    └── auth.php
```

If `App::getLocale()` returns `hi`, the package reads `resources/lang/hi/auth.php`.
