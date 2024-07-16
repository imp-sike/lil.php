<?php

namespace App\Model;

use Stevel\Model;

class Blog extends Model {
    protected $table = "blog";

    protected $fillable = ['title', 'description'];
}
