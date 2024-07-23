<?php
use Lil\Routes;

Routes::get("/", function() {
    return view('index');
}, 'index');