<?php
namespace Auth\Logout\Services;

defined('ROOT') or die("Direct script access denied");

interface ILogoutService
{
    public function logoutService();
}