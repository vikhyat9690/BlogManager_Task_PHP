<?php
require_once "../Blog/BlogRepository.php";
require_once "../Blog/BlogService.php";
require_once "../../config/Database.php";
require_once "../Blog/Blog.php";

use Config\Database;
use Blog\BlogService;
use Blog\BlogRepository;
use Blog\Blog;

session_start();
header("Content-Type: application/json");
if(!isset($_SESSION['user_id'])) {
    header("Location: ../../public/login.php");
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);
$blogId = $data['id'] ?? '';


if(!$blogId || !is_numeric($blogId)) {
    echo json_encode(["success" => false, "message" => "No valid id found."]);
    exit;
}
$conn = (new Database())->connect();
$repository = new BlogRepository($conn);
$service = new BlogService($repository);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
            $blog = $service->getPostById($blogId);
            if(!$blog || $blog->getUserId() !== $_SESSION['user_id']) {
                echo json_encode(["success" => false, "message" => "Unauthorized action performed"]);
                exit;
            }

            if($service->deletePost($blogId)) {
                echo json_encode(['success' => true, "message" => 'Blog Deleted Successfully!!']);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to delete the blog"]);
            }
    } catch (Exception $e) {
        echo "Error :" . json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}