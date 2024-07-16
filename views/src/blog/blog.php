<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        .blog-parent {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .blog {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .blog .title {
            font-weight: bold;
            font-size: 1.2em;
            color: #333;
        }

        .blog .description {
            color: #666;
            margin-top: 10px;
        }

        .blog .actions {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }

        .blog .actions a,
        a,
        button {
            text-decoration: none;
            color: #333;
            padding: 8px 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            transition: background-color 0.3s ease;
        }

        .blog .actions a:hover,
        a:hover,
        button:hover {
            background-color: #eee;
        }

        button {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1>Blogs Management</h1>
    <a href="<?= route('admin.blog.add'); ?>">Add New Blog</a>
    <br>
    <br>

    <div class="blog-parent">
        <?php foreach ($data["blogs"] as $blog): ?>
            <div class="blog">
                <div class="title"><?= $blog["title"]; ?></div>
                <div class="description"><?= $blog["description"]; ?></div>
                <div class="actions">
                    <a href="<?= route('admin.blog.edit', ["id" => $blog["id"]]); ?>">Edit</a>
                    <form action="<?= route('admin.blog.delete', ['id' => $blog['id']]); ?>" method="POST">
                        <?= csrf(); ?>
                        <button type="submit">Delete</button>
                    </form>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>