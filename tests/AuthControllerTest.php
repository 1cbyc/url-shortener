<?php

use PHPUnit\Framework\TestCase;
use UrlShortener\Controller\AuthController;
use UrlShortener\Model\User;

class AuthControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
        User::truncate();
    }

    public function testRegisterAndLogin()
    {
        $_POST = [
            'email' => 'test@example.com',
            'password' => 'password123'
        ];
        ob_start();
        (new AuthController())->register();
        $output = ob_get_clean();
        $this->assertEquals('Registered', $output);
        $this->assertNotEmpty($_SESSION['user_id']);

        $_POST = [
            'email' => 'test@example.com',
            'password' => 'password123'
        ];
        ob_start();
        (new AuthController())->login();
        $output = ob_get_clean();
        $this->assertEquals('Logged in', $output);
        $this->assertNotEmpty($_SESSION['user_id']);
    }

    public function testRegisterDuplicateEmail()
    {
        User::create([
            'email' => 'dupe@example.com',
            'password' => password_hash('password', PASSWORD_ARGON2ID),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $_POST = [
            'email' => 'dupe@example.com',
            'password' => 'password123'
        ];
        ob_start();
        (new AuthController())->register();
        $output = ob_get_clean();
        $this->assertEquals('Email already registered', $output);
    }
} 