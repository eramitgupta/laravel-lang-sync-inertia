# Examples

## Installation

Install both package halves:

```bash
composer require erag/laravel-lang-sync-inertia
npm install @erag/lang-sync-inertia
```

If the app does not already have published Laravel language files:

```bash
php artisan lang:publish
```

Then publish the package config:

```bash
php artisan erag:install-lang
```

## Controller Example

Use `syncLangFiles()` before returning the Inertia page:

```php
<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        syncLangFiles(['auth', 'messages']);

        return Inertia::render('Dashboard');
    }
}
```

## Settings Profile Example

A common host-app integration point is a settings page controller like `ProfileController`. The package should be used before `Inertia::render(...)`:

```php
<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        syncLangFiles(['auth', 'profile', 'validation']);

        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }
}
```

## Language File Example

```php
<?php

return [
    'greeting' => 'Hello!',
    'welcome' => 'Welcome, :name',
    'legacy_welcome' => 'Welcome, {name}',
    'notifications' => '{0} No notifications|{1} One notification|[2,*] :count notifications',
];
```

## Vue Example

```vue
<script setup lang="ts">
import { lang } from '@erag/lang-sync-inertia/vue';

const { __, trans, transChoice, trans_choice } = lang();
</script>

<template>
    <section>
        <h1>{{ __('auth.greeting') }}</h1>
        <p>{{ trans('auth.welcome', { name: 'Amit' }) }}</p>
        <p>{{ __('auth.legacy_welcome', { name: 'Taylor' }) }}</p>
        <p>{{ transChoice('auth.notifications', 3) }}</p>
        <p>{{ trans_choice('auth.notifications', 0) }}</p>
    </section>
</template>
```

Expected output:

```text
Hello!
Welcome, Amit!
Welcome, Taylor!
3 notifications
No notifications
```

## React Example

```tsx
import { lang } from '@erag/lang-sync-inertia/react';

export default function Dashboard() {
    const { __, trans, transChoice } = lang();

    return (
        <section>
            <h1>{__('auth.greeting')}</h1>
            <p>{trans('auth.welcome', { name: 'Amit' })}</p>
            <p>{transChoice('auth.notifications', 1)}</p>
        </section>
    );
}
```

## Multiple Group Example

Load multiple translation groups when the page uses more than one namespace:

```php
syncLangFiles(['auth', 'pagination', 'validation']);
```

That produces a shared prop like:

```php
[
    'lang' => [
        'auth' => [...],
        'pagination' => [...],
        'validation' => [...],
    ],
]
```

## Raw Inertia Prop Example

Read the raw translations when a helper wrapper is not needed:

```ts
import { usePage } from '@inertiajs/vue3';

const { lang } = usePage().props;
```

```tsx
import { usePage } from '@inertiajs/react';

const { lang } = usePage().props;
```

## JSON Export Example

Generate frontend JSON files from PHP language groups:

```bash
php artisan erag:generate-lang
```

Input:

```php
// lang/en/auth.php
return [
    'greeting' => 'Hello!',
    'welcome' => 'Welcome, :name',
];
```

Output:

```json
{
    "greeting": "Hello!",
    "welcome": "Welcome, :name"
}
```

Location:

```text
{output_lang}/en/auth.json
```

## Legacy Import Example

The root package import still works for older codebases:

```ts
import { vueLang } from '@erag/lang-sync-inertia';

const { __, trans } = vueLang();
```

```tsx
import { reactLang } from '@erag/lang-sync-inertia';

const { __, trans } = reactLang();
```
