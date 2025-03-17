<?php
namespace Auth\UsersManager\Services;

use App\Core\Http\Response;
use App\Core\Models\Security;
use App\Core\Models\Session;
use Auth\UsersManager\Models\User;
use Auth\UsersManager\Models\UserModel;
use Auth\UsersManager\Models\UserRolesMapModel;

defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class UserService extends Response implements IUserService
{
    private $model;
    private $session;

    public function __construct()
    {
        $this->model    = new UserModel;
        $this->session  = new Session;
    }

    public function UserListService()
    {
        $data = $this->model->getList();
        if($data){ return $this->success("Tüm Kullanıcılar Listelendi",$data)->send(); }
        else{ return $this->success("Kullanıcılar Bulunamadı",[])->send(); }
    }

    public function UserFirstService($userId)
    {
        $data = $this->model->getUser($userId);
        if($data){ return $this->success("Kullanıcı Listelendi",$data)->send(); }
        else{ return $this->info("Kullanıcı Bulunamadı",'info',404)->send(); }
    }
    
    public function addUserService(User $user,$password_retype,$role_select,$lastinsertId = true)
    {
        $userValidate = $this->userValidate($user);
        if($userValidate)
            return $userValidate;
        
        $passwordValidate = $this->passwordValidate($user);
        if($passwordValidate)
            return $passwordValidate;
        
        if($user->password != $password_retype)
            return $this->info(__lang("passwords_do_not_match"))->send();

        $userControl = $this->model->avaibleControl($user->email);
        if($userControl)
            return $this->info(__lang("personnel_email_already_exists"))->send();

        $data = [
            "user_firstname" => $user->firstname,
            "user_lastname"  => $user->lastname,
            "user_email"     => $user->email,
            "user_phone"     => $user->phone,
            "id_number"      => $user->idNumber,
            "status"         => $user->status,
            "user_password"  => Security::securityPassword($user->password),
            "date_created"   => date("Y-m-d H:i:s")
        ];
        $addData = $this->model->addUserModel($data,$lastinsertId);
        if($addData)
        {
            if($role_select!="")
            {
                $addRole = new UserRolesMapModel();
                $addRole->addRoleManager([
                    "role_id" => $role_select,
                    "user_id" => $addData
                ]);
            }
            return $this->success(__lang("personnel_add_created_successfully"))->send();
        }
        else
            return $this->error(__lang("something_went_wrong_try_again_later"))->send();
    }

    public function updateUserService(User $user,$role_select)
    {
        $userValidate = $this->userValidate($user);
        if($userValidate)
            return $userValidate;

        if($user->password != "")
        {
            $passwordValidate = $this->passwordValidate($user);
            if($passwordValidate)
                return $passwordValidate;
        }

        $userControl = $this->model->avaibleControl(null,$user->userId);
        if($userControl)
        {
            $emailControl = $this->model->avaibleControl($user->email,$user->userId);
            if($emailControl)
                return $this->info(__lang("personnel_email_already_exists"))->send();

            $data = [
                "user_firstname" => $user->firstname,
                "user_lastname"  => $user->lastname,
                "user_email"     => $user->email,
                "user_phone"     => $user->phone,
                "id_number"      => $user->idNumber,
                "status"         => $user->status,
                "date_updated"   => date("Y-m-d H:i:s")
            ];
            if($user->password != "")
                $data["user_password"] = Security::securityPassword($user->password);

            $userUpdate = $this->model->updateUserModel($user->userId,$data);
            if($userUpdate) 
            {
                $userRole = new UserRolesMapModel();
                $userRoleControl = $userRole->userRoleControl($user->userId);
                if($userRoleControl)
                {
                    if($role_select!="")
                        $userRole->updateUserRole($userRoleControl->id,["role_id" => $role_select]);
                    else
                        $userRole->deleteUserRole($userRoleControl->id);
                }else
                {
                    $userRole->addRoleManager([
                        "role_id" => $role_select,
                        "user_id" => $user->userId
                    ]);
                }
                return $this->success(__lang("personnel_update_successfully"))->send();
            }
            else
                return $this->error(__lang("something_went_wrong_try_again_later"))->send();
        }
        else{
            return $this->info(__lang("personnel_not_found"))->send();
        }
        
    }

    public function deleteUserService($userId)
    {
        if (empty($userId))
            return $this->info(__lang("personnel_id_cannot_be_empty"))->send();

        $userControl = $this->model->getUser($userId);
        if($userControl)
        {
            $delete = $this->model->deleteUserModel($userId);
            if($delete)
            {
                $userRole = new UserRolesMapModel();
                $userRoleControl = $userRole->userRoleControl($userId);
                if($userRoleControl)
                    $userRole->deleteUserRole($userRoleControl->id);
                    return $this->success(__lang("personnel_delete_successfully"))->send();
            }
            else
                return $this->error(__lang("something_went_wrong_try_again_later"))->send();
        }else
            return $this->error("BVurası çalıştı")->send();
            

    }
   
    private function userValidate(User $user)
    {
        if (empty($user->firstname))
            return $this->info(__lang("personnel_name_cannot_be_empty"))->send();
        if (empty($user->lastname))
            return $this->info(__lang("personnel_surname_cannot_be_empty"))->send();
        if (empty($user->phone))
            return $this->info(__lang("phone_cannot_be_empty"))->send();
        if (empty($user->email))
            return $this->info(__lang("email_cannot_be_empty"))->send();
        if (!validateEmail($user->email))
            return $this->info(__lang("invalid_email_format"))->send();

        return false;
    }

    private function passwordValidate(User $user)
    {
        if (empty($user->password))
            return $this->info(__lang("password_cannot_be_empty"))->send();
        if (strlen($user->password) < 8) 
            return $this->info(__lang("password_must_be_at_least_8_characters"))->send();

        return false;
    }
}