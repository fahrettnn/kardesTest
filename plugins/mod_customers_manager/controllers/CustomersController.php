<?php

namespace Mod\Customers\Controllers;

use App\Core\Helpers\UrlHelper;
use App\Core\Http\Request;
use Mod\Customers\Services\CustomersService;

defined('ROOT') or die("Direct script access denied");

class CustomersController extends Request
{
    public $services;

    public function __construct()
    { $this->services = new CustomersService(); }

    public function getList()
    {
        if ($this->method() == "GET") {
            $customerId = $this->get("customerId") ?: UrlHelper::URL(2);
            if (!empty($customerId))
                return $this->services->getCustomerService($customerId);
            else
                return $this->services->getListService();
        } else {
            return $this->services->errorResponse("Hatalı İstek");
        }
    }

    
}