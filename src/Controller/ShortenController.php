<?php

namespace UrlShortener\Controller;

use UrlShortener\Service\UrlService;
use UrlShortener\Service\RateLimiter;
use UrlShortener\Service\Csrf;

class ShortenController
{
    public function shorten()
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid CSRF token']);
            return;
        }
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $limiter = new RateLimiter();
        if ($limiter->tooManyRequests($ip)) {
            http_response_code(429);
            echo json_encode(['error' => 'Too many requests']);
            return;
        }
        $limiter->logRequest($ip);
        $url = $_POST['url'] ?? '';
        $custom = $_POST['custom'] ?? null;
        $expires_at = $_POST['expires_at'] ?? null;
        if ($custom && !isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Authentication required for custom codes']);
            return;
        }
        $service = new UrlService();
        $result = $service->shorten($url, $custom, $expires_at);
        header('Content-Type: application/json');
        echo json_encode($result);
    }
} 