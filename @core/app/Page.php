<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'pages';
    protected $fillable = ['title','slug','page_content','status','visibility','page_builder_status','layout','sidebar_layout','navbar_variant',
        'page_class','breadcrumb_status','footer_variant','widget_style','left_column','right_column','sidebar_layout_two','sidebar_layout_two_status'];
    public $translatable = ['title','page_content'];

    public function meta_data(){
        return $this->morphOne(MetaData::class,'meta_taggable');
    }
}
