<?php
require_once "../../config/Database.php";
require_once "../../src/Blog/BlogRepository.php";
require_once "../../src/Blog/BlogService.php";
require_once "../../src/Blog/Blog.php";

use Config\Database;
use Blog\BlogService;
use Blog\BlogRepository;
use Blog\Blog;

session_start();
header("Content-Type: text/html; charset=utf-8");
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$editing = isset($_GET['id']) && is_numeric($_GET['id']);
$blogId = $editing ? $_GET['id'] : null;

$blogData = null;
if ($editing) {
    $conn = (new Database())->connect();
    $repo = new BlogRepository($conn);
    $service = new BlogService($repo);
    $blog = $service->getPostById($blogId);

    if (!$blog || $blog->getUserId() !== $_SESSION['user_id']) {
        die("Unauthorized or Blog Not Found");
    }

    $blogData = [
        "title" => $blog->getTitle(),
        "content" => $blog->getContent(),
        "id" => $blog->getId()
    ];
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Blog</title>
    <link rel="stylesheet" href="../assets/style/create.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-latest.debug.min.js"></script>
</head>

<body>
    <div class="main">
        <span><a href="../blogs/index.php">&larr; Back to Blogs</a></span>
        <div class="form-container">
            <h1 data-bind="text: id() ? 'Update Blog' : 'Create Blog'"></h1>
            <form data-bind="submit: submitForm" id="createEditForm" method="post">
                <div class="title">
                    <label for="title">Title: </label>
                    <input type="text" data-bind="value: title" class="title" placeholder="Enter you title">
                </div>
                <div class="content">
                    <label for="content">Content: </label>
                    <textarea data-bind="value: content" id="contentField" class="content" placeholder="Enter you content here..."></textarea>
                </div>
                <button type="submit" data-bind="text: id() ? 'Update' : 'Post'"></button>
            </form>
        </div>
    </div>
    <script>
        const existingData = <?php echo json_encode($blogData ?? new stdClass()); ?>;

        function BlogViewModel() {
            const self = this;
            self.id = ko.observable(existingData.id || null);
            self.title = ko.observable(existingData.title || '');
            self.content = ko.observable(existingData.content || '');

            self.submitForm = function() {
                const isUpdated = !!self.id();
                const handlerUrl = isUpdated ?
                    '../../src/Handler/UpdateHandler.php' :
                    '../../src/Handler/CreateHandler.php';
                fetch(handlerUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: self.id(),
                            title: self.title(),
                            content: self.content()
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            window.location.href = "../blogs/index.php";
                        } else {
                            // alert("Error: ", data.message);

                            console.log(data.message);
                        }
                    })
                    .catch(err => {
                        console.log("Request Failed", err);
                    })
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            ko.applyBindings(new BlogViewModel());
        });
    </script>
</body>

</html>