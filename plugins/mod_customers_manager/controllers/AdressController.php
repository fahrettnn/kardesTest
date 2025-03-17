<?php

namespace Mod\Customers\Controllers;

use App\Core\Helpers\UrlHelper;
use App\Core\Http\Request;

use Mod\Customers\Services\AdressService;

defined('ROOT') or die("Direct script access denied");

class AdressController extends Request
{
    private $service;
    
    public function __construct()
    {
        $this->service = new AdressService;
    }

    

    public function getAdress($adresId)
    { return $this->service->getIdByAdress($adresId); }

    

}