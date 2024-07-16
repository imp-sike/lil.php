<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Page</title>
</head>

<body>
    <nav>
        <a href="<?= route('index'); ?>">Home</a>
        <a href="<?= route('blogs'); ?>">Blogs</a>
        <a href="<?= route('about'); ?>">About</a>
    </nav>
    <h1>Blog Page</h1>
    <div>
        <?php foreach ($data["blogs"] as $blog): ?>
            <div>
                <div class="title"><?= $blog["title"];?></div>
                <div class="description"><?= $blog["description"];?></div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>