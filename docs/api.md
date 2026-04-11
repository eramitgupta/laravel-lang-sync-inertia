---
title: API Helpers
description: Reference for syncLangFiles(), __(), and trans() helpers in Laravel Lang Sync Inertia.
head:
  - ['meta', { name: 'keywords', content: 'Laravel Lang Sync Inertia API, syncLangFiles, trans helper, __ helper, Inertia translations' }]
---

# API Helpers

This package exposes one Laravel helper and two frontend translation helpers.

## `syncLangFiles()`

`syncLangFiles()` is a global Laravel helper provided by this package. Call it inside any controller method to load translation files and share them with your frontend via Inertia.

Think of `syncLangFiles()` as a bridge between your Laravel language files and your Inertia responses.

```php
// Single file
syncLangFiles('auth');

// Multiple files
syncLangFiles(['auth', 'validation', 'pagination']);
```

## Full flow

::: code-group

```php [DashboardController.php]
<?php

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

```ts [Helper usage]
__('auth.greeting')
trans('auth.welcome', { name: 'Amit' })
```

```text [Flow]
Controller -> syncLangFiles('auth')
Laravel -> lang/{locale}/auth.php
Inertia -> page.props.lang.auth
Frontend -> __('auth.greeting')
Output -> Hello!
```

:::

## How it works

When you call `syncLangFiles('auth')` in your controller:

1. Laravel reads `lang/{locale}/auth.php` based on `App::getLocale()`.
2. The translation array is passed to Inertia as a shared prop under `page.props.lang`.
3. The frontend helper reads from `page.props.lang` and resolves keys like `auth.greeting`.
4. Placeholders like `{name}` are replaced at runtime with the values you pass.

## `__(key, replaces?)`

Use `__()` for direct translation lookups.

```ts
__('auth.greeting')
// "Hello!"

__('auth.welcome', { name: 'Amit' })
// "Welcome, Amit!"
```

Direct string keys that contain dots are also supported because the package attempts an exact lookup before nested traversal.

```ts
__('Please proceed with caution, this cannot be undone.')
```

## `trans(key, replaces)`

Use `trans()` when placeholders are expected.

```ts
trans('auth.welcome', { name: 'Amit' })
// "Welcome, Amit!"
```

## `trans()` vs `__()`

| Helper | Best use | Notes |
| --- | --- | --- |
| `__()` | Simple lookup | Great for direct string access and optional replacements |
| `trans()` | Placeholder-based strings | Clearer when replacement values are always required |

Both helpers support placeholder replacement, but `trans()` is the clearer choice when replacements are always present.

## Placeholder replacement

Given this language file:

```php
return [
    'welcome' => 'Welcome, {name}!',
];
```

This is correct:

```ts
trans('auth.welcome', { name: 'Amit' })
__('auth.welcome', { name: 'Amit' })
```

If you pass a plain string instead:

```ts
__('auth.welcome', 'Amit')
```

The placeholder is not replaced.

Use an object whenever placeholder replacement is needed.

## Access raw Inertia props

If you need the full translation object directly:

```ts
import { usePage } from '@inertiajs/vue3'

const { lang } = usePage().props
```

```tsx
import { usePage } from '@inertiajs/react'

const { lang } = usePage().props
```

## TypeScript types

```ts
type Replaces = Record<string, string | number>
type LangValue = string | { [key: string]: string | LangValue }
type LangObject = Record<string, LangValue>
```
