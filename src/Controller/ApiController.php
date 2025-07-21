<?php

namespace UrlShortener\Controller;

use UrlShortener\Service\UrlService;
use UrlShortener\Model\Url;
use UrlShortener\Model\Click;
use UrlShortener\Service\RateLimiter;

class ApiController
{
    public function shorten()
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $limiter = new RateLimiter();
        if ($limiter->tooManyRequests($ip)) {
            http_response_code(429);
            echo json_encode(['error' => 'Too many requests']);
            return;
        }
        $limiter->logRequest($ip);
        $data = json_decode(file_get_contents('php://input'), true);
        $url = $data['url'] ?? '';
        $custom = $data['custom'] ?? null;
        $expires_at = $data['expires_at'] ?? null;
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

    public function analytics($vars)
    {
        $code = $vars['code'] ?? '';
        $url = Url::where('code', $code)->first();
        if (!$url) {
            http_response_code(404);
            echo json_encode(['error' => 'Not found']);
            return;
        }
        $clicks = Click::where('url_id', $url->id)->get();
        header('Content-Type: application/json');
        echo json_encode(['clicks' => $clicks]);
    }
} 