<?php
namespace Mod\Customers\Services;


defined('ROOT') or die("Direct script access denied");

interface ICustomers
{
    public function getListService();
    public function getCustomerService($customerId);
}