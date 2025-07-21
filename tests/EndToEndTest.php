<?php

use PHPUnit\Framework\TestCase;

class EndToEndTest extends TestCase
{
    protected static $serverProcess;
    protected static $baseUrl = 'http://localhost:8081';

    public static function setUpBeforeClass(): void
    {
        $docRoot = realpath(__DIR__ . '/../public');
        self::$serverProcess = proc_open(
            "php -S localhost:8081 -t $docRoot",
            [
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w']
            ],
            $pipes
        );
        sleep(1);
    }

    public static function tearDownAfterClass(): void
    {
        if (self::$serverProcess) {
            proc_terminate(self::$serverProcess);
        }
    }

    public function testShortenAndResolveAndAnalytics()
    {
        $csrf = $this->getCsrfToken();
        $data = [
            'url' => 'https://e2e-test.com',
            'csrf_token' => $csrf
        ];
        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($opts);
        $result = file_get_contents(self::$baseUrl . '/shorten', false, $context);
        $json = json_decode($result, true);
        $this->assertArrayHasKey('short_url', $json);
        $shortUrl = $json['short_url'];
        $code = basename($shortUrl);

        $headers = get_headers($shortUrl, 1);
        $this->assertStringContainsString('https://e2e-test.com', $headers["Location"] ?? '');

        $analytics = file_get_contents(self::$baseUrl . "/api/analytics/$code");
        $analyticsJson = json_decode($analytics, true);
        $this->assertArrayHasKey('clicks', $analyticsJson);
        $this->assertGreaterThanOrEqual(1, count($analyticsJson['clicks']));
    }

    protected function getCsrfToken()
    {
        $html = file_get_contents(self::$baseUrl . '/');
        if (preg_match('/name="csrf_token" value="([^"]+)"/', $html, $m)) {
            return $m[1];
        }
        $this->fail('CSRF token not found');
    }
} 