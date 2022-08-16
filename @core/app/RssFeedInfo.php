<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RssFeedInfo extends Model
{
    use HasFactory;
    protected $fillable = ['link','automation_type','imported_item','status','last_import_time'];
}
