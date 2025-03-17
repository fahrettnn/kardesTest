<?php
namespace Auth\UsersManager\Models;

use \App\Core\Database\Model;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Respect\Validation\Validator as v;

defined('ROOT') or die("Direct script access denied");

/**
 * {ApiModel} class
 */
class UserRolesMapModel extends Model
{
    private $table_name = 'tbl_user_roles_map';
	private $primary_key = 'id';

	public function getListRoles($user_id)
	{
		$result = $this->table("tbl_user_roles_map") // 
    			->whereRaw("disabled = 0 AND id IN (SELECT role_id FROM user_roles_map WHERE disabled = 0 AND user_id =?)", [$user_id]) // Koşulları ayarlayın
    			->get();
    	return $result ?: false;
	}



	public function addRoleManager(array $data)
	{
		$insert = $this->table($this->table_name)->addCreate($data);
		return $insert ?: false;
	}

	public function userRoleControl($user_id)
	{
		$control = $this->table($this->table_name)->where("user_id",$user_id)->first();
		return $control ?: false;
	}

	public function updateUserRole($id,$data)
	{
		$update = $this->table($this->table_name)->where("id",$id)->update($data);
		return $update ?: false;
	}

	public function deleteUserRole($id)
	{
		$delete = $this->table($this->table_name)->where("id",$id)->delete();
		return $delete ?: false;
	}
    
}