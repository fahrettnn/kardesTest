<?php

namespace Mod\Adress\Controllers;

use App\Core\Helpers\UrlHelper;
use App\Core\Http\Request;
use App\Core\Models\Security;
use Mod\Adress\Models\City;
use Mod\Adress\Services\CityService;

defined('ROOT') or die("Direct script access denied");

class CityController extends Request
{
    private $services;
    
    public function __construct() { $this->services = new CityService(); }

    public function getCityList()
    { 
        if ($this->method() == "GET") {
            $cityId = $this->get("id") ?: UrlHelper::URL(2);
            if (!empty($cityId)) {
                return $this->services->getCityService($cityId);
            } else {
                return $this->services->getListService();
            }
        } else {
            return $this->services->errorResponse("Hatalı İstek");
        }
    }

    public function addCity()
    {
        if($this->method() == "POST")
        {
            $city = new City;
            $city->city_name = $this->post("city_name");
            $city->status    = $this->post("city_status");

            return $this->services->addCityService($city); 
        }
        else
            return $this->services->errorResponse("Hatalı İstek");
    }
        
    public function editCity()
    {
        if($this->method() == "PUT")
        {
            $city = new City;
            $city->id        = $this->put("id");
            $city->city_name = $this->put("city_name");
            $city->status    = $this->put("status");

            return $this->services->editCityService($city); 
        }
        else
            return $this->services->errorResponse("Hatalı İstek");
    }

    public function deleteCity()
    {
        if($this->method() == "DELETE")
        {
            $cityId  = $this->delete("cityId") ?: UrlHelper::URL(2);
            return $this->services->deleteCityService($cityId); 
        }
        else
            return $this->services->errorResponse("Hatalı İstek");
    }
}