<!-- index.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to LilPHP</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        
        .container h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
        }
        
        .container p {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 20px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        
        .btn:hover {
            background-color: #0056b3;
        }
        
        .logo {
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://raw.githubusercontent.com/imp-sike/lil.php/main/logo.png" alt="LilPHP Logo" class="logo" width="150">
        <h1>Welcome to LilPHP</h1>
        <p>A minimal PHP toolkit inspired by Laravel</p>
        <a href="#" class="btn">Explore LilPHP</a>
    </div>
</body>
</html>
