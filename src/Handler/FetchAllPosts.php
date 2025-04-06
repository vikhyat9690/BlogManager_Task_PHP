<?php

require_once "../Blog/BlogRepository.php";
require_once "../Blog/BlogService.php";
require_once "../../config/Database.php";
require_once "../Blog/Blog.php";

use Config\Database;
use Blog\BlogService;
use Blog\BlogRepository;

session_start();
header("Content-Type: application/json");


$conn = (new Database())->connect();
$repository = new BlogRepository($conn);
$service = new BlogService($repository);

try {
    $blogs = $service->getAllPosts();
    echo json_encode(["success" => true, "blogs" => $blogs]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}