<?php

namespace Mod\Customers;

defined('ROOT') or die("Direct script access denied");

class Districts 
{
    public int $district_id;
    public string $city_id;
    public string $district_name;
    public string $deleted;
    public string $date_updated;
    public string $date_deleted;
}