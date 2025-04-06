<?php
namespace Auth;

use mysqli;

class RegisterService
{
    private $conn;

    public function __construct(
        mysqli $conn
    )
    {
        $this->conn = $conn;
    }

    public function register(string $username, string $email, $password): bool {
        $checkStmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $checkStmt->bind_param('s', $email);
        $checkStmt->execute();
        if($checkStmt->num_rows() > 0) {
            return false;
        }
        $checkStmt->close();
        $hashPass = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $username, $email, $hashPass);
        if($stmt->execute()) {
            return true;
        }
        $stmt->close();

        return false;
    }
}