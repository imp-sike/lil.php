<?php

namespace App\Controller;

use App\Model\Blog;
use Exception;
use Lil\Controller;

class BlogController extends Controller
{
    // Add controller methods here
    public function index()
    {
        $blogs = new Blog();
        return view('blog/blog', ["blogs" => $blogs->all()]);
    }

    public function add()
    {
        return view('blog/add');
    }

    public function edit($id) {
        $blog = new Blog();
        $data = $blog->withId($id);
        if ($data == null) {
            return redirect(route('admin.blog'));
        }
        return view('blog/edit', ["blog" => $data]);
    }

    public function store() {
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
    }

    public function update($id) {
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
    }

    public function delete($id) {
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
    
    
    }
}

