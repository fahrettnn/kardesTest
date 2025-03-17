<?php

namespace Auth\Login\Controllers;

use App\Core\Http\Request;
use App\Core\JWTService;
use App\Core\Models\Security;
use Auth\Login\Services\LoginService;

defined('ROOT') or die("Direct script access denied");

class LoginController extends Request
{
    private $services;

    public function __construct()
    { $this->services = new LoginService(); }

    public function login()
    { return $this->services->loginService($this->post()); }

}