<?php

namespace UrlShortener\Controller;

use UrlShortener\Model\User;
use UrlShortener\Service\SecurityLogger;

class AuthController
{
    public function register()
    {
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            http_response_code(400);
            echo 'Invalid input';
            SecurityLogger::log('register_invalid_input', ['email' => $email]);
            return;
        }
        if (User::where('email', $email)->exists()) {
            http_response_code(409);
            echo 'Email already registered';
            SecurityLogger::log('register_duplicate_email', ['email' => $email]);
            return;
        }
        $user = User::create([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_ARGON2ID),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $_SESSION['user_id'] = $user->id;
        SecurityLogger::log('register_success', ['user_id' => $user->id, 'email' => $email]);
        echo 'Registered';
    }

    public function login()
    {
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $user = User::where('email', $email)->first();
        if (!$user || !password_verify($password, $user->password)) {
            http_response_code(401);
            echo 'Invalid credentials';
            SecurityLogger::log('login_failed', ['email' => $email]);
            return;
        }
        $_SESSION['user_id'] = $user->id;
        SecurityLogger::log('login_success', ['user_id' => $user->id, 'email' => $email]);
        echo 'Logged in';
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        SecurityLogger::log('logout');
        echo 'Logged out';
    }
} 