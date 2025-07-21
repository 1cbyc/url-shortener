<?php

namespace UrlShortener\Controller;

use UrlShortener\Service\UrlService;

class RedirectController
{
    public function redirect($vars)
    {
        $code = $vars['code'] ?? '';
        $service = new UrlService();
        $url = $service->resolve($code);
        if ($url) {
            header('Location: ' . $url, true, 302);
            exit;
        }
        http_response_code(404);
        echo 'Not Found';
    }
} 