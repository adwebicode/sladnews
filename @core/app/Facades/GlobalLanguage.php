<?php

namespace App\Facades;

use App\Helpers\LanguageHelper;
use Illuminate\Support\Facades\Facade;


class GlobalLanguage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'GlobalLanguage';
    }
}