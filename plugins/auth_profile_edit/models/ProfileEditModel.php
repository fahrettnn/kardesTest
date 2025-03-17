<?php
namespace Auth\ProfileEdit\Models;

use \App\Core\Database\Model;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Respect\Validation\Validator as v;

defined('ROOT') or die("Direct script access denied");

/**
 * {ApiModel} class
 */
class ProfileEditModel extends Model
{
    private $table_name = "tbl_users";
	private $primary_key = 'user_id';

    public function getFirst($user_email)
    {
        $control = $this->table($this->table_name)->where('user_email',$user_email)->first();
        return $control ?: false;
    }

    public function updateData(Profile $profile)
	{
        $data = [
            'user_firstname'    => $profile->userFirstname,
            'user_lastname'     => $profile->userLastname,
            'user_phone'        => $profile->userPhone,
            'date_updated'      => date("Y-m-d H:i:s"),
        ];

        if (!empty($profile->userPassword))
            $data['user_password'] = $profile->userPassword;
    
        if ($profile->userImg !='')
            $data['user_img'] = $profile->userImg;

		return $this->table($this->table_name)->where('user_email',$profile->userEmail)->update($data);
	}
    
}