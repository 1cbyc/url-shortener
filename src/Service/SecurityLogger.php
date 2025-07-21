<?php

namespace UrlShortener\Service;

class SecurityLogger
{
    public static function log($event, $details = [])
    {
        $entry = [
            'timestamp' => date('c'),
            'event' => $event,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'details' => $details
        ];
        file_put_contents(__DIR__ . '/../../logs/security.log', json_encode($entry) . "\n", FILE_APPEND);
    }
} 