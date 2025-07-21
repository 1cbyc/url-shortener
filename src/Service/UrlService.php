<?php

namespace UrlShortener\Service;

use Illuminate\Database\Capsule\Manager as Capsule;
use UrlShortener\Model\Url;
use UrlShortener\Model\Click;

class UrlService
{
    public function shorten($url, $custom = null, $expires_at = null)
    {
        if (!$this->isValidUrl($url)) {
            return ['error' => 'Invalid URL'];
        }
        if ($custom) {
            $custom = $this->sanitizeCustomCode($custom);
            if (!$this->isValidCustomCode($custom)) {
                return ['error' => 'Custom code must be 3-32 alphanumeric characters'];
            }
        }
        if ($expires_at) {
            $expires_at = $this->sanitizeExpiry($expires_at);
            if (!$expires_at) {
                return ['error' => 'Invalid expiry date'];
            }
        }
        $code = $custom ?: $this->generateCode();
        $exists = Capsule::table('urls')->where('code', $code)->exists();
        if ($exists) {
            return ['error' => 'Code already exists'];
        }
        $user_id = $_SESSION['user_id'] ?? null;
        Capsule::table('urls')->insert([
            'code' => $code,
            'url' => $url,
            'user_id' => $user_id,
            'expires_at' => $expires_at,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        return ['short_url' => getenv('APP_URL') . '/' . $code];
    }

    public function resolve($code)
    {
        $row = Url::where('code', $code)->first();
        if ($row) {
            if ($row->expires_at && strtotime($row->expires_at) < time()) {
                return null;
            }
            Click::create([
                'url_id' => $row->id,
                'referrer' => $_SERVER['HTTP_REFERER'] ?? null,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
                'country' => null,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return $row->url;
        }
        return null;
    }

    protected function generateCode($length = 6)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }

    protected function isValidUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    protected function sanitizeCustomCode($code)
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', $code);
    }

    protected function isValidCustomCode($code)
    {
        return preg_match('/^[a-zA-Z0-9]{3,32}$/', $code);
    }

    protected function sanitizeExpiry($expiry)
    {
        $ts = strtotime($expiry);
        if ($ts && $ts > time()) {
            return date('Y-m-d H:i:s', $ts);
        }
        return null;
    }
} 