<?php

namespace Auth\Logout\Controllers;

use App\Core\Helpers\UrlHelper;
use App\Core\Http\Request;
use App\Core\Models\Security;
use App\Core\Models\Session;
use Auth\Logout\Services\LogoutService;

defined('ROOT') or die("Direct script access denied");

class LogoutController extends Request
{
    private $services;
    private $session;

    public function __construct()
    { 
        $this->session = new Session;
        
        
        //$this->services = new LogoutService(); 
    }

    public function logout()
    {  
        if($this->session->is_logged_in())
        {
            $ses = new Session;
            $ses->logout();
            UrlHelper::redirect("auth/logins");
        }
        else
            UrlHelper::redirect("auth/loginsaa");
    }
}