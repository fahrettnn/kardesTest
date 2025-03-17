<?php

namespace Auth\UserRoles\Controllers;

use App\Core\Helpers\UrlHelper;
use App\Core\Http\Request;
use App\Core\Models\Security;
use Auth\UserRoles\Models\Role;
use Auth\UserRoles\Services\UserRolesService;

defined('ROOT') or die("Direct script access denied");

class UserRolesController extends Request
{
    private $services;
    
    public function __construct()
    {
        $this->services = new UserRolesService();
    }

    public function getRole()
    {
        $getData = $this->get();
        if(isset($getData['roleId']))
            return $this->getRoleFirst($getData['roleId']);
        else if(UrlHelper::URL(3)!="")
            return $this->getRoleFirst(UrlHelper::URL(3));
        else
            return $this->getRoleList();
    }

    public function getRoleList() { return $this->services->getRoleList(); }
    public function getRoleFirst($roleId) { return $this->services->getRoleFirst($roleId); }
 
    public function addRole() 
    { 
        $role = new Role;
        $role->role     = Security::SecurityCode($this->post("role_name"));
        $role->disabled = Security::SecurityCode($this->post('role_status'));
        
        return $this->services->addRole($role); 
    }

    public function updateRole() { 
        $role           = new Role();
        $role->id       = !empty(UrlHelper::URL(3)) ? Security::SecurityCode(UrlHelper::URL(3)) : (Security::SecurityCode($this->put('roleId')) ?? null);
        $role->role     = Security::SecurityCode($this->put('role_name')) ?? null;
        $role->disabled = Security::SecurityCode($this->put('role_status')) ?? null;
        return $this->services->updateRole($role); 
    }

    public function deleteRole() 
    {     
        $roleId = !empty(UrlHelper::URL(2)) ? Security::SecurityCode(UrlHelper::URL(2)) : (Security::SecurityCode($this->delete('roleId')));
        return $this->services->deleteRole($roleId); 
    }
}