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
class UserModel extends Model
{
    private $table_name = 'tbl_users';
	private $primary_key = 'user_id';

	public function getList()
	{
		$data = $this->table($this->table_name)->whereRaw("user_type!=?",[0])->get();
		if($data)
		{
			foreach ($data as $key => $value) 
			{
				$rolles = $this->table("tbl_user_roles_map")
								->select(["tbl_user_roles_map.*","tbl_user_roles.*"])
								->leftjoin("tbl_user_roles","role_id","id")
								->where("tbl_user_roles_map.user_id",$value->user_id)->first();
				if($rolles)
					$data[$key]->roles = $rolles;
				else
					$data[$key]->roles = null;
			}
		}
		return $data ?: false;
	}

	public function getUser(string $id)
	{
		$userfirst = $this->table($this->table_name)->whereRaw("user_id=? AND user_type!=?",[$id,0])->first();
		return $userfirst ?: false;
	}

	public function addUserModel(array $data){ return $this->table($this->table_name)->addCreate($data,true); }

	public function avaibleControl($email=null,$userId = null)
	{
		$data = $this->table($this->table_name);
		if($email== null && $userId!=null)
			$data->whereRaw("user_id=?",[$userId]);
		else if($email!=null && $userId!=null)
			$data->whereRaw("user_email=? AND user_id!=?",[$email,$userId]);
		else
			$data->where("user_email",$email);
		
		return $data->first() ?: false;
	}

	public function deleteUserModel(string $id):bool
	{
		$delete = $this->table($this->table_name)->where("user_id",$id)->delete();
		return $delete ? true : false;
	}

	public function updateUserModel(string $id,array $data):bool
	{
		$update = $this->table($this->table_name)->where("user_id",$id)->update($data);
		return $update ? true : false;
	}
}