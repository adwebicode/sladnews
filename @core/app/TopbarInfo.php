<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TopbarInfo extends Model
{
    use HasFactory;
    protected $fillable = ['icon','url','title'];
}
