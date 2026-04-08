---
title: React
description: Use Laravel Lang Sync Inertia translations in Inertia React pages and components.
head:
  - ['meta', { name: 'keywords', content: 'Laravel Lang Sync Inertia React, Inertia React translations, React lang helper, trans helper React, __ helper React' }]
---

# React

Use the React entry point to access Laravel translations inside Inertia React pages and components.

The package reads translations from `page.props.lang`, then gives you two helpers:

- `__()` for simple lookups
- `trans()` for placeholder replacement

## Import the helper

```tsx
import { lang } from '@erag/lang-sync-inertia/react'

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

```tsx [resources/js/Pages/Dashboard.tsx]
import { lang } from '@erag/lang-sync-inertia/react'

export default function Dashboard() {
  const { trans, __ } = lang()

  return (
    <section>
      <h1>{__('auth.greeting')}</h1>
      <p>{trans('auth.welcome', { name: 'John' })}</p>
    </section>
  )
}
```

```text [Output]
Hello!
Welcome, John!
```

:::

## Component example

```tsx
import { lang } from '@erag/lang-sync-inertia/react'

export default function Login() {
  const { trans, __ } = lang()

  return (
    <div>
      <h1>{__('auth.greeting')}</h1>
      <p>{trans('auth.welcome', { name: 'Amit' })}</p>
    </div>
  )
}
```

## How it works

1. Laravel loads `resources/lang/{locale}/auth.php` with `syncLangFiles('auth')`.
2. Inertia shares that data under `page.props.lang`.
3. `lang()` reads from those props inside your React component.
4. Keys like `auth.greeting` and `auth.welcome` resolve automatically.

## `trans()` vs `__()`

| Function | Best for | Description |
| --- | --- | --- |
| `trans()` | Dynamic values | Use when passing placeholders like `{ name }` |
| `__()` | Simple lookups | Shortcut for quick string access |

```tsx
__('auth.greeting')
// Hello!

trans('auth.welcome', { name: 'Amit' })
// Welcome, Amit!
```

Both helpers support placeholder replacement, but `trans()` is the clearer choice when replacements are always present.

## Placeholder replacements

Always pass an object when you want `{name}` style placeholders replaced:

```tsx
trans('auth.welcome', { name: 'Amit' })
__('auth.welcome', { name: 'Amit' })
```

If you pass a plain string instead:

```tsx
__('auth.welcome', 'Amit')
```

The placeholder is not replaced.

## Access raw Inertia props

If you need the full translation object directly in React:

```tsx
import { usePage } from '@inertiajs/react'

const { lang } = usePage().props
```

## Legacy API

The older React helper still works:

```tsx
import { reactLang } from '@erag/lang-sync-inertia'

const { trans, __ } = reactLang()
```

Use `@erag/lang-sync-inertia/react` for new code.
