<?php

namespace UrlShortener\Controller;

use UrlShortener\Model\User;
use UrlShortener\Model\Url;
use UrlShortener\Model\Click;

class AdminController
{
    public function dashboard()
    {
        $userCount = User::count();
        $urlCount = Url::count();
        $clickCount = Click::count();
        require __DIR__ . '/../../views/admin_dashboard.php';
    }
} 