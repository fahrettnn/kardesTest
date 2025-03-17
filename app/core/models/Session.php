<?php

namespace App\Core\Models;
use \App\Core\Helpers\ActionFilterHelper;

defined('ROOT') or die("Direct script access denied");

class Session
{
    private $varKey = 'APP';
    private $userKey = 'User';

    public function getUser()
    {
        return $this->get($this->userKey);;
    }

    private function startSession():int
    {
        if(session_status() === PHP_SESSION_NONE)
            session_start();

        return 1;
    }

    public function set($key, $value): void
    {
        $this->startSession();
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null): mixed
    {
        $this->startSession();
        return $_SESSION[$key] ?? $default;
    }

    public function pop(string $key):mixed
    {
        $this->startSession();
        if(!empty($_SESSION[$this->varKey][$key]))
        {   
            $var = $_SESSION[$this->varKey][$key];
            unset($_SESSION[$this->varKey][$key]);
            return $var;
        }

        return false;
    }

    public function remove(string $key):bool
    {
        $this->startSession();

        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }

        return false;
    }

    public function auth(object|array $row)
    {
        $this->startSession();
        $_SESSION[$this->userKey] = $row;;
    }

    public function is_admin():bool
    {
        if(!$this->is_logged_in())
            return false;

        $arr = ActionFilterHelper::doFilter('before_check_admin',['is_admin'=>false]);

        if($arr['is_admin'])
            return true;

        return false;
    }

    public function is_logged_in():bool
    {
        $this->startSession();

        if(empty($_SESSION[$this->userKey]))
            return false;

        if(is_object($_SESSION[$this->userKey]))
            return true;

        if(is_array($_SESSION[$this->userKey]))
            return true;

        return false;
    }

    public function reset():bool
    {
        session_destroy();
        session_regenerate_id();
        return true;
    }

    public function logout():bool
    {
        $this->startSession();

        if(!empty($_SESSION[$this->userKey]))
            unset($_SESSION[$this->userKey]);

        return true;
    }

    public function user(string $key = ''):mixed
    {
        $this->startSession();

        if(!empty($_SESSION[$this->userKey]))
        {
            if(empty($key))
                return $_SESSION[$this->userKey];

            if(is_object($_SESSION[$this->userKey]))
            {
                if(!empty($_SESSION[$this->userKey]->$key))
                    return $_SESSION[$this->userKey]->$key;
            }else
            if(is_array($_SESSION[$this->userKey]))
            {
                if(!empty($_SESSION[$this->userKey][$key]))
                    return $_SESSION[$this->userKey][$key];
            }
        }

        return null;
    }  

    public function all():mixed
    {
        $this->startSession();
        if(!empty($_SESSION))
        {   
            return $_SESSION;
        }

        return null;
    }
}