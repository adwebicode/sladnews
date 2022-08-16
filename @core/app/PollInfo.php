<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollInfo extends Model
{
    use HasFactory;

    protected $table = 'poll_infos';
    protected $fillable = ['poll_id','name','email','vote_name'];

    public function poll(){
        return $this->belongsTo(Poll::class, 'poll_id');
    }
}
