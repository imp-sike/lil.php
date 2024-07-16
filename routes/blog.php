<?php
use App\Model\Blog;
use Stevel\Routes;

// Way to view blogs
Routes::get('/admin/blog', function () {
    $blogs = new Blog();
    return view('blog/blog', ["blogs" => $blogs->all()]);
}, 'admin.blog');

Routes::get('/admin/blog/add', function () {
    return view('blog/add');
}, 'admin.blog.add');

Routes::get('/admin/blog/edit/{id}', function ($id) {
    $blog = new Blog();
    $data = $blog->withId($id);
    if($data == null) {
        return redirect(route('admin.blog'));
    }
    return view('blog/edit', ["blog" => $data]);
}, 'admin.blog.edit');

Routes::post('/admin/blog/store', function () {
    // return view('blog/add');
    $data = mustHave($_POST, ["title", "description"]);
    if ($data) {
        $blog = new Blog();
        try {
            $blog->insert([
                "title" => $data["title"],
                "description" => $data["description"]
            ]);
            return redirect(route('admin.blog'));
        } catch (Exception $e) {
            echo "Unsuccessfull";
        }
    } else {
        echo "Unsuccessfull";
    }
}, 'admin.blog.store');

Routes::post('/admin/blog/update/{id}', function ($id) {
    // return view('blog/add');
    $data = mustHave($_POST, ["title", "description"]);
    if ($data) {
        $blog = new Blog();
        try {
            $blog->update($id, [
                "title" => $data["title"],
                "description" => $data["description"],
            ]);
            return redirect(route('admin.blog'));
        } catch (Exception $e) {
            echo "Unsuccessfull";
        }
    } else {
        echo "Unsuccessfull";
    }
}, 'admin.blog.update');

Routes::post('/admin/blog/delete/{id}', function ($id) {
    $blog = new Blog();
    $check = $blog->delete($id);
    if($check) {
        return redirect(route('admin.blog'));
    }
}, 'admin.blog.delete');