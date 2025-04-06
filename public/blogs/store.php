<?php
require_once __DIR__ . '../../../config/bootstrap.php';
use Blog\BlogService;
use Blog\BlogRepository;

if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if (empty($title) || empty($content)) {
        die("Title and content are required.");
    }

    $repo = new BlogRepository($mysqli);
    $service = new BlogService($repo);

    $created = $service->createPost($userId, $title, $content);

    if ($created) {
        header('Location: index.php'); 
        exit;
    } else {
        echo "Failed to create blog post.";
    }
}
