<?php

return [

    /*
       |--------------------------------------------------------------------------
       | Language Files Base Path
       |--------------------------------------------------------------------------
       |
       | Specifies the base directory where language files are stored.
       | By default, it points to the "lang" folder in the project root
       | using Laravel's base_path() helper.
       |
       */

    'lang_path' => base_path('lang'),

    /*
    |--------------------------------------------------------------------------
    | Output Path (Exported Files)
    |--------------------------------------------------------------------------
    |
    | Where the package will write generated files
    | like JSON for frontend tooling.
    |
    */

    'output_lang' => resource_path('js/lang'),
];
