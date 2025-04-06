<?php
namespace Auth;

use Core\SessionManager;
use mysqli;

class LoginService
{
    private $conn;
    private $session;

    public function __construct(
        mysqli $conn,
        SessionManager $session
    )
    {
        $this->conn = $conn;
        $this->session = $session;
    }

    public function login(string $email, string $password): bool
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if($result && password_verify($password, $result['password'])) {
            $this->session->set('user_id', $result['id']);
            $this->session->set('email', $result['email']);
            $this->session->set('username', $result['username']);
            return true;
        }
        return false;
    }

    public function logout(): void {
        $this->session->destroy();
    }
}