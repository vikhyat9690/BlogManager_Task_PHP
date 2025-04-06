<?php
require_once "../src/Auth/RegisterService.php";
require_once "../config/Database.php";

use Auth\RegisterService;
use Config\Database;

$db = (new Database())->connect();
$auth = new RegisterService($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($auth->register($username, $email, $password)) {
        echo "Success! User registered Sucessfully";
    } else {
        echo "Error in user registration";
    }
}
?>

<link rel="stylesheet" href="./assets/style/auth.css">
<div class="main">
    <div class="form">
        <h2>Regsitration Form</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Enter you username"><br>
            <input type="email" name="email" placeholder="Enter you email"><br>
            <input type="password" name="password" placeholder="Enter you password"><br>
            <button type="submit">Register</button><br>
            <span>Already a user? <a href="./login.php">Login</a></span>
        </form>
    </div>
</div>