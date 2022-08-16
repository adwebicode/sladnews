<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class InstagramFeed extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'InstagramFeed';
    }
}