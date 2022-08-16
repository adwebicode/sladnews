<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasFactory, HasTranslations;

    protected $table = "faqs";
    protected $fillable = ['title','status','is_open','description'];
    protected $translatable = ['title','description'];
}
