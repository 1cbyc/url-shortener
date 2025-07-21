<?php

namespace UrlShortener\Controller;

use UrlShortener\Service\UrlService;
use UrlShortener\Model\Url;
use UrlShortener\Model\Click;

class ApiController
{
    public function shorten()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $url = $data['url'] ?? '';
        $custom = $data['custom'] ?? null;
        $service = new UrlService();
        $result = $service->shorten($url, $custom);
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