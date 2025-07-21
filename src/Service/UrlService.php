<?php

namespace UrlShortener\Service;

use Illuminate\Database\Capsule\Manager as Capsule;

class UrlService
{
    public function shorten($url, $custom = null)
    {
        $code = $custom ?: $this->generateCode();
        $exists = Capsule::table('urls')->where('code', $code)->exists();
        if ($exists) {
            return ['error' => 'Code already exists'];
        }
        Capsule::table('urls')->insert([
            'code' => $code,
            'url' => $url,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        return ['short_url' => getenv('APP_URL') . '/' . $code];
    }

    public function resolve($code)
    {
        $row = Capsule::table('urls')->where('code', $code)->first();
        return $row ? $row->url : null;
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
} 