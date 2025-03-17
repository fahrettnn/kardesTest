<?php
namespace Auth\Login\Models;

use \App\Core\Database\Model;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Respect\Validation\Validator as v;

defined('ROOT') or die("Direct script access denied");

class LoginModel extends Model
{
    private $table_name = "tbl_users";
	private $primary_key = 'user_id';

    public function login($email,$password)
    {
        $data = $this->table($this->table_name)->whereRaw('user_email=? AND user_password=? AND status=?',[$email,$password,1])->first();
        return $data ?: false;
    }

    public function userControlModel($email,$password = null)
	{
		$data = $this->table($this->table_name);
		if ($password!=null)
			$data->whereRaw('user_email=? AND user_password=? AND status=?',[$email,$password,1]);
		else
			$data->whereRaw("user_email=? AND status=?",[$email,1]);

		return  $data->first() ?: false;
	}

	public function sessionTokenUpdate($email,$session_token)
	{
		$update = $this->table($this->table_name)
            ->where("user_email",$email)
            ->update([
                "last_session" => date("Y-m-d H:i:s"),
                "session_token" => $session_token
            ]);
        return $update ? true : false;
	}

    public function getSessionControl($token)
    {
        $control = $this->table($this->table_name)->whereRaw("session_token=? AND status=?",[$token,1])->first();
        return $control ?? false;
    }
}