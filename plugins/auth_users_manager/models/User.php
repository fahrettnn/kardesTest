<?php 
namespace Auth\UsersManager\Models;

defined('ROOT') or die("Direct script access denied");

class User
{
    public $userId;
    public $firstname;
    public $lastname; 
    public $email; 
    public $password; 
    public $phone;
    public $address;
    public $idNumber;
    public $status;
}