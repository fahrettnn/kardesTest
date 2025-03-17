<?php
namespace Mod\Customers\Services;

use App\Core\Http\Response;
use App\Core\Models\Session;
use Mod\Customers\Models\ContactBook;
use Mod\Customers\Models\ContactBookModel;
use Mod\Customers\Services\IContactBook;

defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class ContactBookService extends Response implements IContactBook
{
    private $model;
    private $session;

    public function __construct()
    {
        $this->model    = new ContactBookModel;
        $this->session  = new Session;
    }

    public function getContactList($customerId)
    {
        
    }

    public function errorResponse($errorMessage,$statusCode = 400) { return $this->error($errorMessage,$statusCode)->send(); }
}