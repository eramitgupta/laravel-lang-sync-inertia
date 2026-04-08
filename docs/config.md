---
title: Config
description: Configure lang_path and output_lang for Laravel Lang Sync Inertia.
head:
  - ['meta', { name: 'keywords', content: 'Laravel Lang Sync Inertia config, inertia-lang.php, lang_path, output_lang, Laravel translation config' }]
---

# Config

After publishing, adjust `config/inertia-lang.php` if required:

## Default config

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Language Files Base Path
    |--------------------------------------------------------------------------
    | The directory where your PHP language files are stored.
    */
    'lang_path' => base_path('lang'),

    /*
    |--------------------------------------------------------------------------
    | Output Path
    |--------------------------------------------------------------------------
    | Where generated JSON files will be written by the Artisan export command.
    */
    'output_lang' => resource_path('js/lang'),

];
```

## `lang_path`

Use `lang_path` to tell the package where your Laravel translation files live.

Default:

```php
'lang_path' => base_path('lang'),
```

This is the directory used when `syncLangFiles()` reads translation files such as:

```text
lang/en/auth.php
lang/hi/auth.php
```

## `output_lang`

Use `output_lang` to define where exported JSON translation files should be generated.

Default:

```php
'output_lang' => resource_path('js/lang'),
```

This path is used by the export command when generating frontend-ready JSON files.

## Example customization

```php
return [
    'lang_path' => resource_path('lang'),
    'output_lang' => resource_path('js/translations'),
];
```
