<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <link rel="stylesheet" href="../assets/style/index.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-latest.debug.min.js"></script>
</head>
<body>
    <div class="main">
        <header>
            <?php include_once "../includes/header.php" ?>
        </header>
        <main>
            <div class="blog-section">
                <h1>Blogs</h1>
                <div data-bind="foreach: blogs" class="blog-container">
                    <div class="blog-card">

                        <h3 data-bind="text: title"></h3>
                        <strong><small>Author: <?= $_SESSION['username'] ?></small></strong><br>
                        <small data-bind="text: created_at"></small>
                        <a data-bind="attr : {href: './blog.php?id=' + id}">Read more</a>

                        <p data-bind="text: content"></p>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <?php include_once "../includes/footer.php" ?>
        </footer>
    </div>

    <script>
        function BlogViewModel() {
            const self = this;

            self.blogs = ko.observableArray([]);

            fetch("../../src/Handler/FetchAllPosts.php")
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    self.blogs(data.blogs);
                } else {
                    alert(data.message);
                }
            })
        }
        ko.applyBindings(new BlogViewModel());
    </script>
</body>
</html>