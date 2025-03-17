<?php
namespace Mod\Customers\Services;

use App\Core\Http\Response;
use Mod\Customers\Models\AdressModel;
use Mod\Customers\Models\CustomersModel;

defined('ROOT') or die("Direct script access denied");

class CustomersService extends Response implements ICustomers
{
    private $customerModel;
    private $adressModel;

    public function __construct()
    { 
        $this->customerModel     = new CustomersModel;
        $this->adressModel       = new AdressModel;
    }

    public function getListService()
    {
        $data   = $this->customerModel->getAllCustomers();
        if($data)
        {
            return $this->success(__lang("all_customer_data_listed"),$data)->send();
        }else{
            return $this->success(__lang("customer_data_not_found"),[])->send();
        }
    }














    
    public function getCustomerService($customerId)
    {
        $data   = $this->customerModel->getFirstCustomer($customerId);
        if($data)
            return $this->success(__lang("all_customer_data_listed"),$data)->send();
        else
            return $this->success(__lang("customer_data_not_found"),[])->send();
    }
    
    public function errorResponse($errorMessage,$statusCode = 400) { return $this->error($errorMessage,$statusCode)->send(); }
}