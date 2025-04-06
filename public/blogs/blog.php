<?php
require_once "../../config/Database.php";
require_once "../../src/Blog/BlogService.php";
require_once "../../src/Blog/BlogRepository.php";
require_once "../../src/Blog/Blog.php";

use Blog\BlogService;
use Blog\BlogRepository;
use Config\Database;
use Blog\Blog;

session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
$id = (int) $_GET['id'] ?? '';
if(!isset($_GET['id'])){
    header("Location: ./index.php");
    die("<script>alert('Blog id is missing')</script>");
}

$conn = (new Database())->connect();
$repository = new BlogRepository($conn);
$service = new BlogService($repository);

try {
    $blog = $service->getPostById($id);
    if(!$blog){
        echo "Blog not found";
        exit;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($blog->getTitle()); ?></title>
    <link rel="stylesheet" href="../assets/style/blog.css">
</head>
<body>
    <div class="main">
        <header>
            <?php include_once "../includes/header.php" ?>
        </header>
        <main>
            <div class="blog-container">
                <div class="blog-section">
                    <div>
                        <div class="title">
                            <h1><?= htmlspecialchars($blog->getTitle()); ?></h1>
                            <span>&#10005;</span>
                        </div>
                    <hr>
                    </div>
                    <p><?= htmlspecialchars($blog->getContent()); ?></p><hr>
                    <div class="bottom">
                    <h4>Author: <?= htmlspecialchars($_SESSION['username']) ?></h4>
                    <small>Posted on: <?= htmlspecialchars($blog->getCreateAt()); ?></small>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <?php include_once "../includes/footer.php" ?>
        </footer>
    </div>
</body>
<script>

</script>
</html>