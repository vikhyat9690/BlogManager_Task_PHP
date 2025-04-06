<?php
require_once "../config/Database.php";
require_once "../src/Auth/LoginService.php";
require_once "../src/Core/SessionManager.php";

use Core\SessionManager;
use Auth\LoginService;
use Config\Database;

$db = (new Database())->connect();
$session = new SessionManager();
$auth = new LoginService($db, $session);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($auth->login($email, $password)) {
        echo "Login Successfull";
        header("Location: ./blogs/create.php");
    } else {
        echo "Invalid Credentials";
    }
}
?>
<link rel="stylesheet" href="./assets/style/auth.css">
<div class="main">
    <div class="form">
        <h2>Login</h2>
        <form method="post">
            <input type="email" name="email" id="email" placeholder="Enter you email"><br>
            <input type="password" name="password" id="password" placeholder="Enter you password"><br>
            <button type="submit">Login</button><br>
            <span>New here? <a href="./register.php">Register</a></span>
        </form>
    </div>
</div>