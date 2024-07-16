<?php
use App\Controller\BlogController;
use Lil\Routes;

// Way to view blogs
Routes::get('/admin/blog', [BlogController::class, 'index'], 'admin.blog');
Routes::get('/admin/blog/add', [BlogController::class, 'add'], 'admin.blog.add');
Routes::get('/admin/blog/edit/{id}', [BlogController::class, 'edit'], 'admin.blog.edit');
Routes::post('/admin/blog/store', [BlogController::class, 'store'], 'admin.blog.store');
Routes::post('/admin/blog/update/{id}', [BlogController::class, 'update'], 'admin.blog.update');
Routes::post('/admin/blog/delete/{id}', [BlogController::class, 'delete'], 'admin.blog.delete');