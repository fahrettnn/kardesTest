<?php

namespace Auth\Reset\Password\Controllers;

use App\Core\Http\Request;
use App\Core\Models\Security;
use Auth\Reset\Password\Services\ResetPasswordService;

defined('ROOT') or die("Direct script access denied");

class ResetPasswordController extends Request
{
    private $services;
    
    public function __construct()
    {
        $this->services = new ResetPasswordService();
    }

    public function resetPassword()
    {
       return $this->services->resetPasswordCode($this->post());
    }

    public function resetPasswordVerify()
    { return $this->services->resetPasswordVerify($this->put()); }

    
}