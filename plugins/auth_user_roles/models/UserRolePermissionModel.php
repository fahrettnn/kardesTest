<?php
namespace Auth\UserRoles\Models;

use \App\Core\Database\Model;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Respect\Validation\Validator as v;

defined('ROOT') or die("Direct script access denied");

/**
 * {ApiModel} class
 */
class UserRolePermissionModel extends Model
{
    private $table_name = "tbl_role_permissions";
	private $primary_key = 'id';

    public function avaibleControl($role_id,$permission)
	{
		$data = $this->table($this->table_name)->whereRaw("role_id=? AND permission=?",[$role_id,$permission])->first();
		return $data ?: false;
	}
    
    public function updateRolePermissions($role_id,$permission,$data)
	{
		$updateData = $this->table($this->table_name)->whereRaw("role_id=? AND permission=?",[$role_id,$permission])->update($data);
		return $updateData ?: false;
	}

	/** Add Permission */
	public function addRolePermissions($roleId,$permission,$disabled)
	{
        $data = [
            'role_id' => $roleId,
            'permission' => $permission,
            'disabled' => $disabled
        ];
		$insert = $this->table($this->table_name)->addCreate($data);
		return $insert ?: false;
	}
}