<?php

namespace {PLUGIN_NAME}\Controllers;

use App\Core\Http\Request;
use App\Core\Models\Security;
use {PLUGIN_NAME}\Services\{CLASS_NAME}Service;

defined('ROOT') or die("Direct script access denied");

class {CLASS_NAME}Controller extends Request
{
    private $services;
    
    public function __construct()
    {
        $this->services = new {CLASS_NAME}Service(new \{PLUGIN_NAME}\Models\{CLASS_NAME}Model());
    }

    public function requestController()
    {
        switch ($this->method()) {
            case 'GET':
                echo $this->getController();
                break;
            case 'POST':
                $postData = $this->post();
                echo $this->addController($postData);
                break;
            case 'PUT':
                echo $this->updateController($this->put());
                break;
            case 'DELETE':
                echo $this->deleteController($this->delete('xxxx'));
                break;
        }
    }

    public function getController()
    { return $this->services->getListService(); }

    /**
     * Company Controller ile servis arasında iletişimi tutan fonksiyon 
     * amacı gelen post datalarını nesne ile eşleştirerek servisteki ilgili sınıfı çağırıp geriye dönenen sonucu iletmek
     */
    public function addController($postData)
    {
        ${CLASS_NAME} = new \{PLUGIN_NAME}\Models\{CLASS_NAME};
        ${CLASS_NAME}->xxxx = Security::SecurityCode($postData["xxxx"]);
        ${CLASS_NAME}->xxxx = Security::SecurityCode($postData["xxxx"]);
        ${CLASS_NAME}->xxxx = Security::SecurityCode($postData["xxxx"]);
        ${CLASS_NAME}->xxxx = Security::SecurityCode($postData["xxxx"]);
        ${CLASS_NAME}->xxxx = Security::SecurityCode($postData["xxxx"]);

        return $this->services->addCreateService(${CLASS_NAME},false);
    }

    public function updateController($putData)
    {
        ${CLASS_NAME} = new \{PLUGIN_NAME}\Models\{CLASS_NAME};
        ${CLASS_NAME}->id   = Security::SecurityCode($putData["xxxxx"]);
        ${CLASS_NAME}->xxxx = Security::SecurityCode($putData["xxxx"]);
        ${CLASS_NAME}->xxxx = Security::SecurityCode($putData["xxxx"]);
        ${CLASS_NAME}->xxxx = Security::SecurityCode($putData["xxxxx"]);
        ${CLASS_NAME}->xxxx = Security::SecurityCode($putData["xxxx"]);
        ${CLASS_NAME}->xxxx = Security::SecurityCode($putData["xxxx"]);
        ${CLASS_NAME}->xxxx = date("Y-m-d H:i:s");

        return $this->services->updateService(${CLASS_NAME});
    }

    public function deleteController($field_id)
    { return $this->services->deleteService($field_id); }

}