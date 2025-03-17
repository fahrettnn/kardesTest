<?php

namespace Settings\Controllers;

use App\Core\Http\Request;
use App\Core\Models\Session;
use Settings\Services\SettingService;

defined('ROOT') or die("Direct script access denied");

class SettingController extends Request
{
    private $services;
    private $session;
    public function __construct() { 
        $this->services = new SettingService(); 
        $this->session  = new Session();
    }

    public function requestSettings()
    {
        if(!$this->session->is_logged_in())
            return jsonResponse(401,"info",__lang("unauthorized_access"));
        
        if(user_can("genel_ayarlar_düzenleme"))
        {
            if($this->method() == "PUT")
                echo $this->mailSetting($this->put());
            else
                echo jsonResponse(400,"info",__lang("invalid_request"));
        }else
            echo jsonResponse(401,"info",__lang("unauthorized_access"));
        
    }

    private function mailSetting($putData)
    { 
        return $this->services->mailSetting($putData); 
    }

}