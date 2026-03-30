# 🌐 Laravel Lang Sync Inertia

<center>
    <img width="956" alt="Screenshot" src="https://github.com/user-attachments/assets/bbefb4c4-e435-45ab-954a-17eafa1405ee">
</center>

<div align="center">

[![Packagist License](https://img.shields.io/badge/Licence-MIT-blue)](https://github.com/eramitgupta/laravel-lang-sync-inertia/blob/main/LICENSE)
[![Latest Stable Version](https://img.shields.io/packagist/v/erag/laravel-lang-sync-inertia?label=Stable)](https://packagist.org/packages/erag/laravel-lang-sync-inertia)
[![Total Downloads](https://img.shields.io/packagist/dt/erag/laravel-lang-sync-inertia.svg?label=Downloads)](https://packagist.org/packages/erag/laravel-lang-sync-inertia)

</div>

**Laravel Lang Sync Inertia** is a Laravel package that bridges your backend translation files with your Inertia.js frontend (Vue 3 or React). Load any language file in a controller and access it instantly on the frontend — no manual prop passing required.

---

## ✨ Features

- ⚙️ Automatic translation sharing via Inertia.js middleware
- 📂 Load single or multiple language files with one call
- 🔄 Dynamic placeholder replacement — `{name}` syntax
- 🧩 Works with both **Vue 3** and **React**
- 🌍 Auto-switches locale folder based on Laravel's current locale
- 🆕 Artisan command to export `.php` lang files to frontend-ready `.json`
- 🛠️ Clean helper API: `trans()` and `__()`

---

## 📦 Installation

**Step 1** — Install the package via Composer:

```bash
composer require erag/laravel-lang-sync-inertia
```

**Step 2** — Publish Laravel's default language files (if not already published):

```bash
php artisan lang:publish
```

> 📁 This creates the `lang/` directory in your project root with default Laravel translation files.

**Step 3** — Publish the package config and composables:

```bash
php artisan erag:install-lang
```

---

## ⚠️ Frontend Companion Package (Required)

To use translations in Vue or React, install the NPM package:

```bash
npm install @erag/lang-sync-inertia
```

📘 Full frontend docs: [npmjs.com/package/@erag/lang-sync-inertia](https://www.npmjs.com/package/@erag/lang-sync-inertia)

---

## 🚀 Usage — `syncLangFiles()`

`syncLangFiles()` is a global Laravel helper provided by this package. Call it inside any controller method to load translation files and share them with your frontend via Inertia.

> ✅ Think of `syncLangFiles()` as a bridge — it reads your `.php` lang files on the backend and passes them as Inertia shared props to your frontend components.

```php
// Single file
syncLangFiles('auth');

// Multiple files
syncLangFiles(['auth', 'validation', 'pagination']);
```

---

## 🔁 Step-by-Step Example

### 1. Define a Language File

📁 `resources/lang/en/auth.php`

```php
return [
    'greeting' => 'Hello!',
    'welcome'  => 'Welcome, {name}!',
];
```

### 2. Load It in the Controller

```php
use Inertia\Inertia;

public function login()
{
    syncLangFiles('auth');

    return Inertia::render('Login');
}
```

This loads `resources/lang/{locale}/auth.php` based on `App::getLocale()` and shares it automatically with Inertia.

### 3. Use It on the Frontend

#### ✅ Vue 3

```vue
<script setup>
import { lang } from '@erag/lang-sync-inertia/vue'

const { trans, __ } = lang()
</script>

<template>
    <h1>{{ __('auth.greeting') }}</h1>
    <p>{{ trans('auth.welcome', { name: 'John' }) }}</p>
</template>
```

#### ✅ React

```tsx
import { lang } from '@erag/lang-sync-inertia/react'

export default function Login() {
    const { trans, __ } = lang()

    return (
        <div>
            <h1>{__('auth.greeting')}</h1>
            <p>{trans('auth.welcome', { name: 'John' })}</p>
        </div>
    )
}
```

### 📤 Output

```
Hello!
Welcome, John!
```

---

## 🧪 How It Works

When you call `syncLangFiles('auth')` in your controller:

1. Laravel reads `resources/lang/{locale}/auth.php` based on `App::getLocale()`
2. The translation array is passed to Inertia as a shared prop under `page.props.lang`
3. The frontend helper (`lang()`) reads from `page.props.lang` and resolves keys like `auth.greeting`
4. Placeholders like `{name}` are replaced at runtime with the values you pass

```
Controller → syncLangFiles('auth')
    ↓
Laravel reads resources/lang/en/auth.php
    ↓
Inertia shares → page.props.lang.auth
    ↓
Frontend → __('auth.greeting') or trans('auth.welcome', { name: 'Amit' })
    ↓
Output → "Hello!" / "Welcome, Amit!"
```

---

## 🧠 `trans()` vs `__()`

| Function  | Best For | Description |
|-----------|----------|-------------|
| `trans()`  | Dynamic values | Use when passing placeholders like `{ name }` |
| `__()`     | Simple lookups | Shortcut for quick string access |

Both functions support placeholder replacement. `trans()` is recommended when replacements are always present.

```ts
// Simple
__('auth.greeting')
// → "Hello!"

// With replacement
trans('auth.welcome', { name: 'Amit' })
// → "Welcome, Amit!"
```

---

## 🛠 Example with Plain String

Sometimes you may pass a plain string instead of a replacement object:

```ts
__('auth.welcome', 'Amit')
// → "Welcome, {name}!" (placeholder is NOT replaced — string is appended as-is)
```

> ⚠️ This does **not** replace `{name}`. Always use an object when you need placeholder replacement.

The correct approach:

```ts
trans('auth.welcome', { name: 'Amit' })
// → "Welcome, Amit!"

__('auth.welcome', { name: 'Amit' })
// → "Welcome, Amit!"
```

---

## 📡 Access Raw Inertia Props

If you need the full translation object directly:

**Vue:**

```ts
import { usePage } from '@inertiajs/vue3'

const { lang } = usePage().props
```

**React:**

```tsx
import { usePage } from '@inertiajs/react'

const { lang } = usePage().props
```

---

## 🗂️ Translation File Structure

Language files are loaded dynamically based on `App::getLocale()`:

```
resources/lang/
├── en/
│   └── auth.php
├── hi/
│   └── auth.php
```

Calling `syncLangFiles('auth')` loads `resources/lang/{locale}/auth.php` automatically.

---

## ⚙️ Configuration

After publishing, customize `config/inertia-lang.php`:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Language Files Base Path
    |--------------------------------------------------------------------------
    | The directory where your .php language files are stored.
    */
    'lang_path' => base_path('lang'),

    /*
    |--------------------------------------------------------------------------
    | Output Path (Exported JSON Files)
    |--------------------------------------------------------------------------
    | Where generated .json files will be written by the Artisan command.
    */
    'output_lang' => resource_path('js/lang'),

];
```

---

## 🆕 Artisan Command — Export to JSON

Generate frontend-ready `.json` files from your `.php` language files — useful when you want to use translations without Inertia shared props.

```bash
php artisan erag:generate-lang
```

**Input:** `resources/lang/en/auth.php`

```php
return [
    'greeting' => 'Hello!',
    'welcome'  => 'Welcome, {name}!',
];
```

**Output:** `resources/js/lang/en/auth.json`

```json
{
    "greeting": "Hello!",
    "welcome": "Welcome, {name}!"
}
```

Generated structure:

```
resources/js/lang/
├── en/
│   ├── auth.json
│   ├── validation.json
│   └── pagination.json
├── hi/
│   ├── auth.json
│   └── validation.json
```

---

## 📄 License

Licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

## 🤝 Contributing

Pull requests and issues are welcome! Let's make Laravel localization better together. 💬
