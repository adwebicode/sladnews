<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Poll extends Model
{
    use HasFactory;

    protected $table = 'polls';
    protected $fillable = ['question','options','vote','status','name','email'];

    public function poll_infos(){
        return $this->hasMany(PollInfo::class);
    }
}
