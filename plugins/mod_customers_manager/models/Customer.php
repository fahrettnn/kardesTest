<?php

namespace Mod\Customers;

defined('ROOT') or die("Direct script access denied");

class Customer
{
    public int $customer_id;
    public string $company_name;
    public string $company_email;
    public string $company_phone;
    public string $company_fax;
    public string $company_web;
    public string $company_adres_id; 
    public string $company_status;
    public $date_update;
    public string $added_by_user_id;
}
