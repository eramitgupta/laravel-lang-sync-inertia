<p align="center"><h1 align="center">Laravel Lang Sync Inertia</h1></p>

<p align="center">
<a href="https://github.com/eramitgupta/laravel-lang-sync-inertia/blob/main/LICENSE"><img src="https://img.shields.io/badge/Licence-MIT-blue" alt="License"></a>
<a href="https://packagist.org/packages/erag/laravel-lang-sync-inertia"><img src="https://img.shields.io/packagist/v/erag/laravel-lang-sync-inertia?label=Stable" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/erag/laravel-lang-sync-inertia"><img src="https://badge.laravel.cloud/badge/erag/laravel-lang-sync-inertia" alt="Laravel Compatibility"></a>
<a href="https://packagist.org/packages/erag/laravel-lang-sync-inertia"><img src="https://img.shields.io/packagist/dt/erag/laravel-lang-sync-inertia.svg?label=Downloads" alt="Total Downloads"></a>
</p>

## About Laravel Lang Sync Inertia

Laravel Lang Sync Inertia is a lightweight package for sharing Laravel translation files with Inertia.js applications. It provides a simple way to make backend language files available inside Vue 3, React, and Svelte pages without manually passing translation props in every response.

## Features

- 🔄 Automatic translation sharing through Inertia.js shared props.
- 📦 Load single or multiple Laravel language files with `syncLangFiles()`.
- 🗂️ Nested language directories via dot notation, e.g. `syncLangFiles('admin.users')` → `__('admin.users.name')`.
- 🧩 Dedicated Vue 3, React, and Svelte helpers from `@erag/lang-sync-inertia`.
- 📝 Use clean frontend helpers like `__()`, `trans()`, `transChoice()`, and `trans_choice()`.
- ✨ Laravel-style placeholder replacement with `:name` values.
- 🧱 Legacy `{name}` placeholder support for existing translation files.
- 🔢 Pluralization support with Laravel-style exact and interval choices.
- 🌐 Locale-aware loading from `lang/{locale}` using Laravel's current app locale.
- 📤 Export PHP language files to frontend-ready JSON with `php artisan erag:generate-lang`.
- ↩️ Direct string fallback when a translation key is not found.
- 🛠️ Configurable language source and JSON output paths.
- ✅ TypeScript-ready frontend helper package.

## Documentation

Documentation for Laravel Lang Sync Inertia can be found at https://erag.in/laravel-lang-sync-inertia/.

## Installation

Install the Laravel package using Composer:

```bash
composer require erag/laravel-lang-sync-inertia
```

Install the frontend helper package using npm:

```bash
npm install @erag/lang-sync-inertia
```

NPM package repository: https://github.com/eramitgupta/lang-sync-inertia

## Contributing

Thank you for considering contributing to Laravel Lang Sync Inertia.

## Support the Project ⭐

If you find Laravel Lang Sync Inertia useful, please consider giving it a star on GitHub. It helps the project grow and reach more developers!


## License

Laravel Lang Sync Inertia is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
