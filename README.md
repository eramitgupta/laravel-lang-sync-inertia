# 🌐 Laravel Easy Translation Sync with Inertia (Vue.js / React)

<center>
    <img width="956" alt="Screenshot" src="https://github.com/user-attachments/assets/bbefb4c4-e435-45ab-954a-17eafa1405ee">
</center>
<div align="center">

[![Packagist License](https://img.shields.io/badge/Licence-MIT-blue)](https://github.com/eramitgupta/laravel-lang-sync-inertia/blob/main/LICENSE)
[![Latest Stable Version](https://img.shields.io/packagist/v/erag/laravel-lang-sync-inertia?label=Stable)](https://packagist.org/packages/erag/laravel-lang-sync-inertia)
[![Total Downloads](https://img.shields.io/packagist/dt/erag/laravel-lang-sync-inertia.svg?label=Downloads)](https://packagist.org/packages/erag/laravel-lang-sync-inertia)

</div>

**Laravel Lang Sync Inertia** helps you add different languages to your Laravel app with Vue or React. It makes translations easy! 

## ✨ Features

* ⚙️ Inertia.js integration with automatic sharing
* 📂 Load single or multiple language files
* 🔄 Dynamic replacement support in translations
* 🧩 Supports both Vue.js and React
* 🧵 Built-in middleware for automatic sharing
* 🛠️ Helper functions like `trans()` and `__()` for frontend usage
* 🌍 Automatically switches language folder based on current Laravel locale

---

## 📦 Installation

To install the package, run the following command via Composer:

```bash
composer require erag/laravel-lang-sync-inertia
```

---

## 🛠️ Publish Configuration & Composables

To publish the configuration and composables, run:

```bash
php artisan erag:install-lang
```

---

# ⚠️ Required for Frontend (Vue/React)

To use translations on your frontend, **📦 you must install the NPM companion package**:

```bash
npm install @erag/lang-sync-inertia
```

📘 Full frontend documentation:
➡ [https://www.npmjs.com/package/@erag/lang-sync-inertia](https://www.npmjs.com/package/@erag/lang-sync-inertia)

---

## 🚀 Usage Guide: `syncLangFiles()`

The `syncLangFiles()` function is a Laravel helper provided by this package. Use it inside your **controller methods** to load translation files and automatically **share them with your Vue or React frontend via Inertia.js**.

> ✅ Think of `syncLangFiles()` as a bridge between Laravel’s backend translations and your Inertia-powered frontend.

---

### 🧠 How to Use

```php
// Load and sync a single translation file
syncLangFiles('auth');

// Load and sync multiple translation files
syncLangFiles(['auth', 'validation', 'pagination']);
```

---

### ✅ Supported Inputs

The `syncLangFiles()` function supports:

* A **string**: For a single translation file
  → `syncLangFiles('auth')`

* An **array of strings**: For multiple translation files
  → `syncLangFiles(['auth', 'validation'])`

---

### 🧪 How It Works

Suppose you have the following language file:

📁 **`resources/lang/en/auth.php`**

```php
return [
    'welcome' => 'Welcome, {name}!',
    'greeting' => 'Hello!',
];
```

Now, you want to show `auth.welcome` and `auth.greeting` on the frontend using Vue or React.

---

### 🔁 Step-by-Step Example

#### 🔹 1. Load Translations in Controller

```php
use Inertia\Inertia;

public function login()
{
    // Load the auth.php language file
    syncLangFiles('auth');

    return Inertia::render('Login');
}
```

🧠 This loads the file `resources/lang/en/auth.php` based on the current Laravel locale and shares its content with Inertia.

---

### 💡 Frontend Usage

#### ✅ Vue Example

```vue
<template>
    <div>
        <h1>{{ __('auth.greeting') }}</h1>
        <p>{{ trans('auth.welcome', { name: 'John' }) }}</p>
    </div>
</template>

<script setup>
import { vueLang } from '@erag/lang-sync-inertia'

const { trans, __ } = vueLang()
</script>
```

#### ✅ React Example

```tsx
import React from 'react'
import { reactLang } from '@erag/lang-sync-inertia'

export default function Login() {
    const { trans, __ } = reactLang()

    return (
        <div>
            <h1>{__('auth.greeting')}</h1>
            <p>{trans('auth.welcome', { name: 'John' })}</p>
        </div>
    )
}
```

---

### 📤 Output on Page

When the above code is rendered, this will be the output:

```
Hello!
Welcome, John!
```

---

### 🧠 Notes on `trans()` vs `__()`

| Function  | Use Case | Description                                                  |
| --------- | -------- | ------------------------------------------------------------ |
| `trans()` | Advanced | Use when you need to pass dynamic placeholders like `{name}` |
| `__()`    | Simple   | Shortcut for quick access to translated strings              |

✅ You can use them interchangeably for basic translations.
✅ Both support placeholder replacement.

---

### 🛠 Example with Plain String

Sometimes, you might want to append something without a key:

```js
__('auth.welcome', 'Vue Developer')
// Output: "Welcome, {name}! Vue Developer" (if placeholder is not used)
```

But recommended usage is always with an object:

```js
trans('auth.welcome', { name: 'Amit' })
// Output: "Welcome, Amit!"
```

---

## 📡 Access Inertia Shared Props

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

You can directly access the full language object shared by Inertia.

---

## 🗂️ Translation File Location

Language files are loaded based on the current Laravel locale. By default, Laravel uses `resources/lang/{locale}` structure:

```
resources/lang/
├── en/
│   └── auth.php
├── hi/
│   └── auth.php
```

When calling:

```php
syncLangFiles('auth');
```

It dynamically loads `resources/lang/{locale}/auth.php`.

---

## ⚙️ Configuration

You can customize the language directory by modifying `config/inertia-lang.php`:

```php
return [
    'lang_path' => base_path('lang'), // Default: /lang
];
```

---

## 🧩 Through:

* `vueLang()` — Vue 3
* `reactLang()` — React

---

## 📄 License

This package is licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

## 🤝 Contributing

Pull requests and issues are welcome!
Let’s work together to improve localization in Laravel! 💬
