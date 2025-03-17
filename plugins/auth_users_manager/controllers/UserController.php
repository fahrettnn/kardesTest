<?php

namespace Auth\UsersManager\Controllers;

use App\Core\Helpers\UrlHelper;
use App\Core\Http\Request;
use App\Core\Models\Security;
use Auth\UsersManager\Models\User;
use Auth\UsersManager\Services\UserService;

defined('ROOT') or die("Direct script access denied");

class UserController extends Request
{
    private $services;
    
    public function __construct()
    {
        $this->services = new UserService();
    }

    public function getList()
    {
        $getData = $this->get();
        if(isset($getData['userId']))
            return $this->getUserFirst($getData['userId']);
        else if(UrlHelper::URL(2)!="")
            return $this->getUserFirst(UrlHelper::URL(2));
        else
            return $this->getUserList();
    }

    private function getUserFirst($userId){ return $this->services->UserFirstService($userId); }
    private function getUserList() { return $this->services->UserListService(); }

    public function createUser() 
    { 
        $user = new User;
        $user->firstname     = Security::SecurityCode($this->post("first_name"));
        $user->lastname      = Security::SecurityCode($this->post("last_name"));
        $user->idNumber      = Security::SecurityCode($this->post("id_number"));
        $user->phone         = Security::SecurityCode($this->post("phone"));
        $user->email         = Security::SecurityCode($this->post("email"));
        $user->address       = Security::SecurityCode($this->post("address"));
        $user->password      = Security::SecurityCode($this->post("password"));
        $user->status        = Security::SecurityCode($this->post("personnel_status"));
        $password_retype     = Security::SecurityCode($this->post("password_retype"));
        $role_select         = Security::SecurityCode($this->post("role_select"));
        return $this->services->addUserService($user,$password_retype,$role_select); 
    }
    public function updateUser() 
    { 
        $user                = new User();
        $user->userId        = Security::SecurityCode($this->put('userId'));
        $user->firstname     = Security::SecurityCode($this->put('e_first_name'));
        $user->lastname      = Security::SecurityCode($this->put('e_last_name'));
        $user->idNumber      = Security::SecurityCode($this->put('e_id_number'));
        $user->phone         = Security::SecurityCode($this->put('e_phone'));
        $user->email         = Security::SecurityCode($this->put('e_email'));
        $user->address       = Security::SecurityCode($this->put('e_address'));
        $user->password      = Security::SecurityCode($this->put('e_password'));
        $user->status        = Security::SecurityCode($this->put('e_personnel_status'));
        $role_select         = Security::SecurityCode($this->put('e_role_select'));
        return $this->services->updateUserService($user,$role_select); 
    }
    public function deleteUser()
    { 
        if(UrlHelper::URL(2)!="")
            $userId =  UrlHelper::URL(2);
        else
            $userId = $this->delete("userId");
        
        return $this->services->deleteUserService($userId); 
    }
}