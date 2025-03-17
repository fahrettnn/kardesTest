<?php
namespace Auth\Reset\Password\Models;

use \App\Core\Database\Model;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Respect\Validation\Validator as v;

defined('ROOT') or die("Direct script access denied");

/**
 * {ApiModel} class
 */
class ResetPasswordModel extends Model
{
    private $table_name = "tbl_password_reset";
	private $primary_key = 'your_table_id';

    public function userEmailControl(string $email)
    {
        return $this->table("tbl_users")->where("user_email",$email)->first();
    }

    public function codeSuccessEmail($email)
    {
        return $this->table('tbl_password_reset')->whereRaw("email=? AND code_status=?",[$email,"1"])->first();
    }

    public function updateCodePasive($email)
    {
        return $this->table("tbl_password_reset")->where('email',$email)->update(["code_status" => "2"]);
    }

    public function addCode($data, $lastinsertId = false)
    {
        return $this->table("tbl_password_reset")->addCreate($data,$lastinsertId);
    }







    

    

    public function updateData($id,$data)
	{
		return $this->table($this->table_name)->where($this->primary_key,$id)->update($data);
	}
    
    
}