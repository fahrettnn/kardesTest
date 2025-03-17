<?php
namespace Auth\UserRoles\Services;

use App\Core\Http\Response;
use App\Core\Models\Session;
use Auth\UserRoles\Models\Role;
use Auth\UserRoles\Models\UserRoles;
use Auth\UserRoles\Models\UserRolesModel;
use Auth\UserRoles\Services\IUserRolesService;

defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class UserRolesService extends Response implements IUserRolesService
{
    private $model;
    private $session;

    public function __construct()
    {
        $this->model    = new UserRolesModel;
        $this->session  = new Session;

        if(!$this->session->is_logged_in())
            return $this->info("Yetkisiz Erişim",401)->send();
    }

    public function getRoleList()
    {
        $userRolesData = $this->model->getList();
        if($userRolesData)
        {
            return $this->success('Tüm roller listelendi',$userRolesData)->send();
        }else
            return $this->success('Tüm roller listelendi',$userRolesData)->send();
    }

    public function getRoleFirst($id)
    {
        if (empty($id))
            return $this->error('Geçersiz Get İsteği')->send();
        
        $userRoleData = $this->model->getFirst($id);
        if($userRoleData):
            return $this->success('Rol listelendi',$userRoleData)->send();
        else:
            return $this->success("Role Bulunamadı",[],404)->send();
        endif;
    }

    public function addRole(Role $role,$lastinsertId = false)
    {
        $roleValidate = $this->RoleValidate($role);
        if($roleValidate)
            return $roleValidate;
        
        $roleControl = $this->model->avaibleControl($role->role);
        if ($roleControl)
            return $this->info("Role Zaten Mevcut")->send();

        else{
            $data = [
                "role" => $role->role,
                "disabled" => $role->disabled
            ];
            $roleAdd = $this->model->add($data,$lastinsertId);
            if($roleAdd)
                return $this->success("Role Başarıyla Oluşturuldu",$roleAdd)->send();

            return $this->error("Bir hata oluştu. Lütfen daha sonra tekrar deneyin.")->send();
        }

        return $this->error("Bir hata oluştu. Lütfen daha sonra tekrar deneyin.")->send();
    }

    public function updateRole(Role $role)
    {
        if (empty($role->id))
            return $this->info("Role İd Boş Olamaz")->send();

        $roleValidate = $this->RoleValidate($role);
        if($roleValidate)
            return $roleValidate;

        $roleControl = $this->model->rolControl($role->id);
        if (!$roleControl)
            return $this->info("Role Bulunamadı","warning")->send();

        $roleNameControl = $this->model->rolNameControl($role->role,$role->id);
        if ($roleNameControl)
            return $this->info("Role Zaten Mevcut","warning")->send();

        $data = [ "role" => $role->role, "disabled" => $role->disabled  ];
        $roleUpdate = $this->model->updateData($role->id,$data);
        if($roleUpdate)
        { return $this->success("Role Başarıyla Güncellendi",$roleUpdate)->send(); }
        else
        { return $this->error("Bir hata oluştu. Lütfen daha sonra tekrar deneyin.")->send(); }
            
    }

    public function deleteRole($roleId)
    {
        $permControl = permissionControlValidation("rolleri_silme");
        if($permControl)
            return $permControl;
        
        if (empty($roleId))
            return $this->info('Rol Id boş olamaz')->send();

        $roleControl = $this->model->rolControl($roleId);
        if (!$roleControl)
            return $this->info('Role Bulunamadı',"warning",404)->send();

        $deleteRole = $this->model->deleteData($roleId);
        if($deleteRole)
            return $this->success('Role başarıyla silindi',$deleteRole)->send();

        return $this->error("Bir hata oluştu. Lütfen daha sonra tekrar deneyin.")->send();
    }


    private function RoleValidate(Role $role)
    {
        if (empty($role->role))
            return $this->info('Role Adı Boş Bırakılamaz')->send();
        if ($role->disabled === null) 
            return $this->info('Role Durumu Seçiniz')->send();

        return false;
    }

}