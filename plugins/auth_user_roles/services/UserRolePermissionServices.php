<?php
namespace Auth\UserRoles\Services;

use App\Core\Http\Response;
use App\Core\Models\Session;
use Auth\UserRoles\Models\UserRolePermissionModel;

defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class UserRolePermissionService extends Response implements IUserRolePermissionService
{
    private $model;
    private $session;

    public function __construct()
    {
        $this->model    = new UserRolePermissionModel;
        $this->session  = new Session;

        if(!$this->session->is_logged_in())
            return $this->info("Yetkisiz Erişim",401)->send();

    }

    public function changePermission($permission, $roleId, $disabled)
    {
        $permissionValidate = $this->permissionValidate($permission, $roleId);
        if($permissionValidate)
            return $permissionValidate;

        $control = $this->model->avaibleControl($roleId,$permission);
        if ($control)
            $permissionsC = $this->model->updateRolePermissions($roleId,$permission,["disabled"=>$disabled]);
        else
            $permissionsC = $this->model->addRolePermissions($roleId,$permission,$disabled);

        if($permissionsC)
            return $this->success("İzin Başarıyle Güncellendi")->send();

        return $this->info("Bir hata oluştu ve izin güncellenmedi","error")->send();
    }

    public function permissionValidate($permission, $roleId)
    {
        if(empty($roleId))
            return $this->info("Role boş olamaz")->send();
        if(empty($permission))
            return $this->info("İzin boş olamaz")->send();

        return false;
    }
}