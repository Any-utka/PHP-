<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Blog</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <header>
        <h1>Welcome to the Book Blog</h1>
        <nav>
            <a href="/">Home</a> |
            <a href="/create">Add Book</a>
        </nav>
    </header>
    
    <main>
        <?php echo $content; ?>
    </main>

    <footer>
        <p>&copy; 2025 Book Blog</p>
    </footer>
</body>
</html>
