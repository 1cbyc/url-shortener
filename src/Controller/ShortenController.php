<?php

namespace UrlShortener\Controller;

use UrlShortener\Service\UrlService;

class ShortenController
{
    public function shorten()
    {
        $url = $_POST['url'] ?? '';
        $custom = $_POST['custom'] ?? null;
        $service = new UrlService();
        $result = $service->shorten($url, $custom);
        header('Content-Type: application/json');
        echo json_encode($result);
    }
} 