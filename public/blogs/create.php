<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
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
        <h1>Create Blog</h1>
        <form data-bind="submit: submitForm" method="post" enctype='multipart/form-data'>
            <div class="title">
                <label for="title">Title: </label>
                <input type="text" data-bind="value: title" class="title" placeholder="Enter you title">
            </div>
            <div class="content">
                <label for="content">Content: </label>
                <textarea data-bind="value: content" id="contentField" class="content" placeholder="Enter you content here..."></textarea>
            </div>
            <button type="submit">Post</button>
        </form>
    </div>
</div>


<script>
    function BlogViewModel() {
        const self = this;
        self.title = ko.observable('');
        self.content = ko.observable('');

        self.submitForm = function () {
            fetch('../../src/Handler/CreateHandler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    title: self.title(),
                    content: self.content()
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert(data.message);
                    window.location.href = "../blogs/index.php";
                } else {
                    // alert("Error: ", data.message);
                    
                    console.log(data.message);
                }
            })
        }
    }

    ko.applyBindings(new BlogViewModel());
</script>
</body>
</html>