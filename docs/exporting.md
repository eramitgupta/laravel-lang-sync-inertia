---
title: Export to JSON
description: Generate frontend-ready JSON translation files from Laravel PHP lang files.
head:
  - ['meta', { name: 'keywords', content: 'Laravel Lang Sync Inertia export to JSON, erag:generate-lang, Laravel lang JSON export, frontend translations' }]
---

# Export to JSON

Use the Artisan command when you want frontend-ready JSON files generated from Laravel PHP lang files.

Generate frontend-ready `.json` files from your `.php` language files when you want to use translations without relying only on Inertia shared props.

## Command

```bash
php artisan erag:generate-lang
```

The generated files are written to the `output_lang` path from `config/inertia-lang.php`.

## Input

File: `resources/lang/en/auth.php`

```php
return [
    'greeting' => 'Hello!',
    'welcome' => 'Welcome, {name}!',
];
```

## Output

File: `resources/js/lang/en/auth.json`

```json
{
  "greeting": "Hello!",
  "welcome": "Welcome, {name}!"
}
```

## Generated structure

```text
resources/js/lang/
├── en/
│   ├── auth.json
│   ├── validation.json
│   └── pagination.json
└── hi/
    ├── auth.json
    └── validation.json
```

## When to use this

- you want static JSON output for the frontend
- you do not want to rely only on Inertia shared props
- you need generated files during a build or deployment flow
