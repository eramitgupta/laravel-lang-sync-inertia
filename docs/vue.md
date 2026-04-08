---
title: Vue
description: Use Laravel Lang Sync Inertia translations in Inertia Vue 3 pages and components.
head:
  - ['meta', { name: 'keywords', content: 'Laravel Lang Sync Inertia Vue, Inertia Vue translations, Vue lang helper, trans helper Vue, __ helper Vue' }]
---

# Vue

Use the Vue entry point to access Laravel translations inside Inertia Vue 3 pages and components.

The package reads translations from `page.props.lang`, then gives you two helpers:

- `__()` for simple lookups
- `trans()` for placeholder replacement

## Import the helper

```ts
import { lang } from '@erag/lang-sync-inertia/vue'

const { trans, __ } = lang()
```

## Full example

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

```php [resources/lang/en/auth.php]
return [
    'greeting' => 'Hello!',
    'welcome' => 'Welcome, {name}!',
];
```

```vue [resources/js/Pages/Dashboard.vue]
<script setup>
import { lang } from '@erag/lang-sync-inertia/vue'

const { trans, __ } = lang()
</script>

<template>
  <section>
    <h1>{{ __('auth.greeting') }}</h1>
    <p>{{ trans('auth.welcome', { name: 'John' }) }}</p>
  </section>
</template>
```

```text [Output]
Hello!
Welcome, John!
```

:::

## Component example

```vue
<script setup lang="ts">
import { lang } from '@erag/lang-sync-inertia/vue'

const { trans, __ } = lang()
</script>

<template>
  <h1>{{ __('auth.greeting') }}</h1>
  <p>{{ trans('auth.welcome', { name: 'Amit' }) }}</p>
</template>
```

## How it works

1. Laravel loads `resources/lang/{locale}/auth.php` with `syncLangFiles('auth')`.
2. Inertia shares that data under `page.props.lang`.
3. `lang()` reads from those props inside your Vue component.
4. Keys like `auth.greeting` and `auth.welcome` resolve automatically.

## `trans()` vs `__()`

| Function | Best for | Description |
| --- | --- | --- |
| `trans()` | Dynamic values | Use when passing placeholders like `{ name }` |
| `__()` | Simple lookups | Shortcut for quick string access |

```ts
__('auth.greeting')
// Hello!

trans('auth.welcome', { name: 'Amit' })
// Welcome, Amit!
```

Both helpers support placeholder replacement, but `trans()` is the clearer choice when replacements are always present.

## Placeholder replacements

Always pass an object when you want `{name}` style placeholders replaced:

```ts
trans('auth.welcome', { name: 'Amit' })
__('auth.welcome', { name: 'Amit' })
```

If you pass a plain string instead:

```ts
__('auth.welcome', 'Amit')
```

The placeholder is not replaced.

## Access raw Inertia props

If you need the full translation object directly in Vue:

```ts
import { usePage } from '@inertiajs/vue3'

const { lang } = usePage().props
```

## Legacy API

The older Vue helper still works:

```ts
import { vueLang } from '@erag/lang-sync-inertia'

const { trans, __ } = vueLang()
```

Use `@erag/lang-sync-inertia/vue` for new code.
