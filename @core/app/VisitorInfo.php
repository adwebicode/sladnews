<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorInfo extends Model
{
    use HasFactory;
    protected $fillable = [
      'reference','ip','os','browser','visited_url','country','device'
    ];
}
