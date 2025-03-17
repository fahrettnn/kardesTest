<?php 
namespace Auth\ProfileEdit\Models;

defined('ROOT') or die("Direct script access denied");

class Profile
{
    public $userId;
    public $userFirstname;
    public $userLastname;
    public $userPhone;
    public $userEmail;
    public $userPassword = '';    
    public $userNewPassword = '';
    public $userNewPasswordRe = '';
    public $userImg = '';
    public $dateUpdated; 
}