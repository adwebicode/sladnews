<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BlogCategory extends Model
{
    use HasFactory, HasTranslations;
    protected $table = 'blog_categories';
    protected $fillable = ['title','status'];
    public $translatable = ['title'];
}
