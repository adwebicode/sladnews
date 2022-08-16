<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasFactory,  HasTranslations;

    protected $table = 'tags';
    protected $fillable = ['name','status'];
    public $translatable = ['name'];
}
