<?php

use PHPUnit\Framework\TestCase;
use UrlShortener\Service\UrlService;
use Illuminate\Database\Capsule\Manager as Capsule;

class UrlServiceTest extends TestCase
{
    protected function setUp(): void
    {
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        Capsule::schema()->create('urls', function ($table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->text('url');
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at');
        });
    }

    public function testShortenAndResolve()
    {
        $service = new UrlService();
        $result = $service->shorten('https://example.com');
        $this->assertArrayHasKey('short_url', $result);
        $code = basename($result['short_url']);
        $resolved = $service->resolve($code);
        $this->assertEquals('https://example.com', $resolved);
    }

    public function testInvalidUrl()
    {
        $service = new UrlService();
        $result = $service->shorten('not-a-url');
        $this->assertArrayHasKey('error', $result);
    }

    public function testCustomCodeValidation()
    {
        $service = new UrlService();
        $result = $service->shorten('https://example.com', 'a!@#');
        $this->assertArrayHasKey('error', $result);
        $result2 = $service->shorten('https://example.com', 'abc123');
        $this->assertArrayHasKey('short_url', $result2);
    }

    public function testDuplicateCode()
    {
        $service = new UrlService();
        $service->shorten('https://example.com', 'abc123');
        $result = $service->shorten('https://another.com', 'abc123');
        $this->assertArrayHasKey('error', $result);
    }
} 