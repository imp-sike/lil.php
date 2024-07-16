<?php
use App\Model\Blog;
use Stevel\Routes;

Routes::get("/", function() {
    return view('index');
}, 'index');

Routes::get("/about", function() {
    return view('about');
}, 'about');

Routes::get("/blogs", function() {
    $blogs = new Blog();
    return view('blogs', ["blogs" => $blogs->all()]);
}, 'blogs');


require __DIR__ . '/blog.php';