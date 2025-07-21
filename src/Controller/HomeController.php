<?php

namespace UrlShortener\Controller;

class HomeController
{
    public function index()
    {
        require __DIR__ . '/../../views/home.php';
    }
} 