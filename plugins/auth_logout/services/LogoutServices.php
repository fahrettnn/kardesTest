<?php
namespace Auth\Logout\Services;

use App\Core\Helpers\UrlHelper;
use App\Core\Http\Response;
use App\Core\Models\Session;
use Auth\Logout\Models\LogoutModel;
use Auth\Logout\Services\ILogoutService;

defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class LogoutService extends Response implements ILogoutService
{
    private $model;
    private $session;

    public function __construct()
    {
        $this->model = new LogoutModel;
        $this->session = new Session;
    }

    public function logoutService()
    { 
        if($this->session->is_logged_in())
        {
            $this->logout();
            UrlHelper::redirect("auth/login");
        }
        else
            UrlHelper::redirect("auth/login");
    }

    public function logout()
    {
        $ses = new Session;
        $ses->logout();
    }

}