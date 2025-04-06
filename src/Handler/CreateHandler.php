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

if(!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$title = $data['title'] ?? '';
$content = $data['content'] ?? '';

if(empty($title) || empty($content)) {
    echo json_encode(["status" => false, "message" => "Fields can't be empty"]);
    exit;
}

$conn = (new Database())->connect();
$repository = new BlogRepository($conn);
$service = new BlogService($repository);

try {
    $id = $_SESSION['user_id'];
    $service->createPost($id, $title, $content);
    echo json_encode(["success" => true, "message" => "Blog Posted Sucessfully!"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}