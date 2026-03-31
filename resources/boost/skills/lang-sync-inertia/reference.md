
# Lang Sync Inertia Reference

## Package Summary

`erag/laravel-lang-sync-inertia` is a Laravel package that syncs backend language
files to Inertia.js frontends and exposes shared translation data for Vue and React.

The package currently provides:
- a helper: `syncLangFiles()`
- service provider integration
- Inertia shared prop: `lang`
- locale-aware loading of generated frontend JSON translations
- runtime + generated translation merging

## Important Files

### `src/LangHelpers.php`
Defines the global helper:

```php
function syncLangFiles(string|array $fileName): array
{
    return Lang::getFile($fileName);
}