<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorData extends Model
{
    use HasFactory;

    protected $table = 'author_data';
    protected $fillable = ['blogable_id','blogable_type','blog_id'];

    public function blogable(){
        return $this->morphTo();
    }

}
