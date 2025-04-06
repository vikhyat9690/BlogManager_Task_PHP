<?php
require_once "../src/Core/SessionManager.php";
use Core\SessionManager;

$session = new SessionManager();

$session->destroy();
header("Location: ./login.php");
?>