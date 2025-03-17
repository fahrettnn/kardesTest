<?php

namespace Mod\Customers\Controllers;

use App\Core\Helpers\UrlHelper;
use App\Core\Http\Request;
use App\Core\Models\Security;
use Mod\Customers\Services\ContactBookService;

defined('ROOT') or die("Direct script access denied");

class ContactBookController extends Request
{
    private $service;
    public function __construct(){ $this->service = new ContactBookService; }

    public function getContactList()
    {
        if ($this->method() == "GET") {
            $customerId = $this->get("customerId") ?: UrlHelper::URL(2) ?? "";
            if (!empty($customerId))
                return $this->service->getContactList($customerId);
            else 
                return $this->service->errorResponse("Hatalı İstek");
        }else{
            return $this->service->errorResponse("Hatalı İstek");
        }
    }
}