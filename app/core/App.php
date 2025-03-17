<?php
namespace App\Core;

use \App\Core\Helpers\ActionFilterHelper;
use \App\Core\Helpers\UrlHelper;
use App\Core\Http\Route;

defined('ROOT') or die("Direct script access denied");

class App
{
    public function index()
    {
        ActionFilterHelper::doAction('before_controller');
        ActionFilterHelper::doAction('controller');
        ActionFilterHelper::doAction('after_controller');

        ob_start();
        ActionFilterHelper::doAction('before_view');
        $before_content = ob_get_contents();
        ActionFilterHelper::doAction('view');
        $after_content  = ob_get_contents();

        if(strlen($after_content) == strlen($before_content))
        {
            if(UrlHelper::page() != '404' && UrlHelper::page() != 'api')
                UrlHelper::redirect('404');
        }

        ActionFilterHelper::doAction('after_view');

        ob_end_flush();
    }

    public function api()
    {
        ob_start();
        $before_content = ob_get_contents();
        ActionFilterHelper::doAction('api');
        $after_content = ob_get_contents();
        ob_end_flush();
    }

    public function permissionControl() 
    {
        ActionFilterHelper::addFilter('user_permissions',function($permissions)
        {
            $ses = new \App\Core\Models\Session;
            if ($ses->is_logged_in())
            {
                $db 	= new \App\Core\Database\Connection;
                $query 	= "SELECT * FROM user_roles";
                $roles 	= $db->query($query);
                if (is_array($roles)) 
                {
                    $user_id = $ses->user("user_id");
                    $query = "select permission from tbl_role_permissions where disabled=0 && role_id in 
                    (select role_id from tbl_user_roles_map where disabled=0 && user_id=:user_id)";
                    $perms = $db->query($query,["user_id" => $user_id]);
                    if ($perms) 
                        $permissions = array_column($perms, 'permission');
                }else { $permissions[] = "all"; }
            }
            return $permissions;
        });
    }

    public function run()
    {   
        $this->permissionControl();
        if (UrlHelper::page() == 'api')
        {
            Route::setBasePath('me-kardes-app.com.tr/');
            $this->api();
            Route::dispatch();
        }
        else
            $this->index();
    }
}