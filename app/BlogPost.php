<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    // protected $table = 'blogposts';
    // Mass Assignement
    protected $fillable = ['title', 'content'];
}
