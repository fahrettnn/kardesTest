<?php

namespace Mod\Customers\Controllers;

use App\Core\Helpers\UrlHelper;
use App\Core\Http\Request;

use Mod\Customers\Services\CityService;

defined('ROOT') or die("Direct script access denied");

class CityController extends Request
{
    private $services;
    
    public function __construct()
    { $this->services = new CityService; }

    public function getCityList()
    {
        if ($this->method() == "GET") {
            $cityId = $this->get("cityId") ?: UrlHelper::URL(3) ?? "";
            if (!empty($cityId)) {
                return $this->services->getCityFirst($cityId);
            } else {
                return $this->services->getListService();
            }
        } else {
            return $this->services->errorResponse("Hatalı İstek");
        }
    }
}