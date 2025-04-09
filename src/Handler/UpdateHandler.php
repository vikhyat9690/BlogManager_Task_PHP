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

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;
$title = $data['title'] ?? '';
$content = $data['content'] ?? '';

if (!$id || empty($title) || empty($content)) {
    echo json_encode(["success" => false, "message" => "Invalid data"]);
    exit;
}

$conn = (new Database())->connect();
$repository = new BlogRepository($conn);
$service = new BlogService($repository);

try {
    $blog = $service->getPostById($id);
    if (!$blog || $blog->getUserId() !== $_SESSION['user_id']) {
        echo json_encode(["success" => false, "message" => "Unauthorized"]);
        exit;
    }

    $service->updatePost($id, $title, $content);
    echo json_encode(["success" => true, "message" => "Blog Updated Successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
