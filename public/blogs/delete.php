<?php

require_once __DIR__ . '../../../config/bootstrap.php';
use Blog\BlogService;

if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit;
}

$blogService = new BlogService($blogRepository);

$blogId = $_GET['id'] ?? null;

if (!$blogId) {
    header('Location: ./blogs/index.php');
    exit;
}

$blog = $blogService->getPostById((int)$blogId);

if (!$blog || $blog['user_id'] != $_SESSION['user_id']) {
    echo "Access Denied.";
    exit;
}

$blogService->deletePost((int)$blogId);

header('Location: ./blogs/index.php');
exit;
