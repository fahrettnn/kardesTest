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
class UserRolesModel extends Model
{
    private $table_name = "tbl_user_roles";
	private $primary_key = 'id';

    public function getList()
    {   
        $result = [];
        $roles = $this->table($this->table_name)->whereRaw("role!=?",["Admin"])->get();
        if($roles)
        {
            foreach ($roles as $role) {
                $permisData = [];
                $permis = $this->table("tbl_role_permissions")->whereRaw("role_id=? AND disabled=?",[$role->id,"0"])->get();
                if($permis)
                {
                    $permisData = array_filter($permis, function ($perms) use ($role) 
                    {
                        return $perms->role_id == $role->id && $perms->disabled == 0;
                    });
                }

                $result[] = (object)[
                    'role' => $role,
                    'permissions'   => $permisData,
                ];

            }
        }
        return $result ?: false;
    }

    public function getFirst($primary_key)
    {
        $result = [];
        $role = $this->table($this->table_name)->whereRaw("role!=? AND id=?",["Admin",$primary_key])->first();
        if($role)
        {
            $permisData = [];
            $permis = $this->table("tbl_role_permissions")->whereRaw("role_id=? AND disabled=?",[$role->id,"0"])->get();
            $permisData = array_filter($permis, function ($perms) use ($role) 
            {
                return $perms->role_id == $role->id && $perms->disabled == 0;
            });

            $result[] = (object)[
                'role' => $role,
                'permissions'   => $permisData,
            ];
        }
        return $result ?: false;
    }

    public function add($data, $lastinsertId = false)
    {   
        return $this->table($this->table_name)->addCreate($data,$lastinsertId);
    }

    public function updateData($id,$data)
	{
		return $this->table($this->table_name)->where($this->primary_key,$id)->update($data);
	}
    
    public function deleteData($id)
	{ 
		$deleteRole = $this->table($this->table_name)->where($this->primary_key,$id)->delete();
        if($deleteRole)
        {
            $permissionCont = $this->table("tbl_role_permissions")->where("role_id",$id)->get();
            if($permissionCont)
                $this->table("tbl_role_permissions")->where("role_id",$id)->delete();

            $userRolMapControl = $this->table("tbl_user_roles_map")->where("role_id",$id)->get();
            if ($userRolMapControl)
                $this->table("tbl_user_roles_map")->where("role_id",$id)->delete();
        }

        return $deleteRole ?: false;
	}
    

    public function avaibleControl($role,$id = null)
	{
		$data = $this->table($this->table_name);

	 	if ($id !== null) 
	 	{
	 		$data->whereRaw("role=? and id!=?",[$role,$id]);
	    }else
	    {
	    	$data->where("role",$role);
	    }

		return $data->first() ? true : false;
	}

    public function rolControl($roleId)
    {
        $data = $this->table($this->table_name)->where("id",$roleId)->first();
        return $data ? true : false;
    }

    public function rolNameControl($role,$roleId)
    {
        $data = $this->table($this->table_name)->whereRaw("id!=? AND role=?",[$roleId,$role])->first();
        return $data ? true : false;
    }
    
}