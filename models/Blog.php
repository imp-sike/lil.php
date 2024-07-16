<?php

namespace App\Model;

use Lil\Model;

class Blog extends Model {
    protected $table = "blog";

    protected $fillable = ['title', 'description'];
}
