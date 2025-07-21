<?php

namespace UrlShortener\Service;

use Illuminate\Database\Capsule\Manager as Capsule;

class RateLimiter
{
    protected $limit = 10;
    protected $window = 60;

    public function tooManyRequests($ip)
    {
        $now = time();
        $windowStart = $now - $this->window;
        $count = Capsule::table('rate_limits')
            ->where('ip', $ip)
            ->where('created_at', '>=', date('Y-m-d H:i:s', $windowStart))
            ->count();
        return $count >= $this->limit;
    }

    public function logRequest($ip)
    {
        Capsule::table('rate_limits')->insert([
            'ip' => $ip,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
} 