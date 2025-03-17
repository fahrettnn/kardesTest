<?php

namespace Mod\Adress\Controllers;

use App\Core\Helpers\UrlHelper;
use App\Core\Http\Request;
use App\Core\Models\Security;
use Mod\Adress\Models\Discrits;
use Mod\Adress\Services\DiscritsService;

defined('ROOT') or die("Direct script access denied");

class DiscritsController extends Request
{
    private $services;
    
    public function __construct()
    {
        $this->services = new DiscritsService();
    }

    public function getList()
    {
        if ($this->method() == "GET") {
            $cityId = $this->get("city_id") ?: UrlHelper::URL(2);
            if (!empty($cityId)) {
                return $this->services->getCityIdAllListService($cityId);
            } else {
                return $this->services->getListService();
            }
        } else {
            return $this->services->errorResponse("Hatalı İstek");
        }
    }
    
    public function add()
    {
        if($this->method() == "POST")
        {
            $discrit = new Discrits;
            $discrit->city_id       = $this->post("city_id");
            $discrit->district_name = $this->post("discrit_name");
            $discrit->status        = $this->post("discrit_status");

            return $this->services->addService($discrit); 
        }
        else
            return $this->services->errorResponse("Hatalı İstek");
    }

    public function editDiscrit()
    {
        if($this->method() == "PUT")
        {
            $discrit                = new Discrits;
            $discrit->id            = $this->put("discritId");
            $discrit->city_id       = $this->put("cityId");
            $discrit->district_name = $this->put("discrit_name");
            $discrit->status        = $this->put("status");

            return $this->services->updateService($discrit); 
        }
        else
            return $this->services->errorResponse("Hatalı İstek");
    }

    public function deleteDiscrit()
    {
        if($this->method() == "DELETE")
        {
            $discritId  = $this->delete("discritId") ?: UrlHelper::URL(2);
            return $this->services->deleteService($discritId); 
        }
        else
            return $this->services->errorResponse("Hatalı İstek");
    }
}