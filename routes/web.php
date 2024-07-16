<?php
use App\Middleware\BearerTokenCheckMiddleware;
use App\Middleware\SampleMiddleware;
use App\Model\Blog;
use Stevel\Routes;

Routes::get("/", function () {
    return view('index');
}, 'index');

Routes::get("/about", function () {
    return view('about');
}, 'about');

Routes::get("/blogs", function () {
    $blogs = new Blog();
    return view('blogs', ["blogs" => $blogs->all()]);
}, 'blogs');

Routes::get("/api", function () {
    $blogs = new Blog();
    return json($blogs->all());
}, 'api', [BearerTokenCheckMiddleware::class]);


require __DIR__ . '/blog.php';