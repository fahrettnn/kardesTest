<?php

namespace Mod\Customers\Controllers;

use App\Core\Helpers\UrlHelper;
use App\Core\Http\Request;

use Mod\Customers\Services\DistrictService;

defined('ROOT') or die("Direct script access denied");

class DistrictController extends Request
{
    private $services;
    
    public function __construct()
    { $this->services = new DistrictService(); }

    public function getCityIdDiscritList()
    {
        if ($this->method() == "GET") {
            $cityId = $this->get("cityId") ?: UrlHelper::URL(3) ?? "";
            if (!empty($cityId)) {
                return $this->services->getCityIdDiscritList($cityId);
            }
        }else {
            return $this->services->errorResponse("Hatalı İstek");
        }
    }
}