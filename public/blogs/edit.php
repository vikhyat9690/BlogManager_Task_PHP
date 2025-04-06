<?php

require_once __DIR__ . '../../../config/bootstrap.php';
use Blog\BlogService;

if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit;
}

$blogService = new BlogService($blogRepository);
$message = '';
$blogId = $_GET['id'] ?? null;

if (!$blogId) {
    header('Location: ./blogs/index.php');
    exit;
}

$blog = $blogService->getPostById((int)$blogId);

if (!$blog || $blog['user_id'] != $_SESSION['user_id']) {
    echo "Access Denied";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($title && $content) {
        $blogService->updatePost((int)$blogId, $title, $content);
        header('Location: ./blogs/index.php');
        exit;
    } else {
        $message = 'All fields are required.';
    }
}
?>

<h2>Edit Blog</h2>
<?php if ($message): ?>
<p style="color: red;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
<form method="post">
    <input type="text" name="title" value="<?= htmlspecialchars($blog['title']) ?>" required><br><br>
    <textarea name="content" rows="5" required><?= htmlspecialchars($blog['content']) ?></textarea><br><br>
    <button type="submit">Update</button>
</form>
