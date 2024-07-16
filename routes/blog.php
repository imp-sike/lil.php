<?php
use App\Model\Blog;
use Lil\Routes;

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
    if ($data == null) {
        return redirect(route('admin.blog'));
    }
    return view('blog/edit', ["blog" => $data]);
}, 'admin.blog.edit');

Routes::post('/admin/blog/store', function () {
    // return view('blog/add');
    $data = mustHave($_POST, ["title", "description"]);
    $upload_path = file_upload("uploads/", "image", ["image/jpeg", "image/png"], 5 * 1024 * 1024);
    if ($upload_path) {
        if ($data) {
            $blog = new Blog();
            try {
                $blog->insert([
                    "title" => $data["title"],
                    "description" => $data["description"],
                    "image" => $upload_path,
                ]);
                return redirect(route('admin.blog'));
            } catch (Exception $e) {
                echo "Unsuccessfull here";
            }
        } else {
            echo "Unsuccessfull there";
        }
    } else {
        echo "File upload failed.";
    }
}, 'admin.blog.store');


// Simplified route handler using the above functions

Routes::post('/admin/blog/update/{id}', function ($id) {
    $data = mustHave($_POST, ["title", "description"]);
    if ($data) {
        $blog = new Blog();
        $c = $blog->withId($id);
        $old_image = $c["image"];

        try {
            // Handle image upload using the new function
            $data["image"] = file_update($_FILES['image'], "uploads/", ["image/jpeg", "image/png"], 5 * 1024 * 1024, $old_image);

            // Update the blog post
            $blog->update($id, $data);
            return redirect(route('admin.blog'));
        } catch (Exception $e) {
            echo "Unsuccessful: " . $e->getMessage();
        }
    } else {
        echo "Unsuccessful: Missing required data.";
    }
}, 'admin.blog.update');

Routes::post('/admin/blog/delete/{id}', function ($id) {
    $blog = new Blog();
    $b = $blog->withId($id);
    $image = $b["image"];

    if (file_delete($image)) {
        $check = $blog->delete($id);
        if ($check) {
            return redirect(route('admin.blog'));
        }
    } else {
        echo "File deletion failed.";
    }


}, 'admin.blog.delete');