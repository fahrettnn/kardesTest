<?php 
namespace Auth_login\Models;

defined('ROOT') or die("Direct script access denied");

class User
{
    public $user_id;
    public $user_firstname;
    public $user_lastname;
    public $user_email;
    public $user_password;
    public $user_phone;
    public $id_number;
    public $adress_id;
    public $user_img;
    public $user_type;
    public $last_session;
    public $session_token;
    public $status;
    public $date_created;
    public $date_updated;
    public $date_deleted;
}