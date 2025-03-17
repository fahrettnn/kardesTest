<?php

namespace Auth\UserRoles\Controllers;

use App\Core\Http\Request;
use App\Core\Models\Security;
use Auth\UserRoles\Services\UserRolePermissionService;

defined('ROOT') or die("Direct script access denied");

class UserRolePermissionController extends Request
{
    private $services;
    
    public function __construct()
    {
        $this->services = new UserRolePermissionService();
    }
    
    public function requestRolePermission()
    {
        switch ($this->method()) {
            case 'POST':
                $postdata = $this->post();
                if ($postdata) 
                    return $this->changePermission(Security::SecurityCode($postdata['permission']), Security::SecurityCode($postdata['role_id']), Security::SecurityCode($postdata['disabled']));
                break;
            case 'PUT':
                $putdata = $this->put();
                if ($putdata) 
                    return $this->changePermission(Security::SecurityCode($putdata['permission']), Security::SecurityCode($putdata['role_id']), Security::SecurityCode($putdata['disabled']));
                break;
        }

    }

    private function changePermission($permission, $roleId, $disabled = 0)
    {
        if ($permission == "tümü")
            $permission = "all";

        return $this->services->changePermission($permission, $roleId, $disabled);
    }

}