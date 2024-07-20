<!-- index.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to LilPHP</title>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
   <link rel="stylesheet" href="views/css/style.css">
</head>

<body>
    <div class="container">
        <img src="https://raw.githubusercontent.com/imp-sike/lil.php/main/logo.png" alt="LilPHP Logo" class="logo"
            width="150">
        <h1>Welcome to LilPHP</h1>
        <p>A minimal PHP toolkit for PHP Lovers</p>
        <div class="routes">
            <?php
            use Lil\Routes;

            Routes::table();
            ?>
        </div>
        <a href="https://github.com/imp-sike/lil.php" target="_blank" class="btn">Explore LilPHP</a>
    </div>

</body>

</html>