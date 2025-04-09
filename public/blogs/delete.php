<?php

require_once __DIR__ . '../../../src/Blog/Blog.php';
require_once __DIR__ . '../../../src/Blog/BlogService.php';
require_once __DIR__ . '../../../src/Blog/BlogRepository.php';
require_once __DIR__ . '../../../config/Database.php';
use Blog\BlogService;
use Config\Database;
use Blog\BlogRepository;
use Blog\Blog;
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$conn = (new Database())->connect();
$blogRepository = new BlogRepository($conn);
$blogService = new BlogService($blogRepository);

$blogId = $_GET['id'] ?? null;

if (!$blogId) {
    header('Location: ./blogs/index.php');
    exit;
}

$blog = $blogService->getPostById((int)$blogId);
if (!$blog || $blog->getUserId() != $_SESSION['user_id']) {
    echo "Access Denied.";
    exit;
}

$blogService->deletePost((int)$blogId);

header('Location: ./index.php');
exit;
