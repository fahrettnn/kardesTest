<?php

namespace Mod\Customers;

defined('ROOT') or die("Direct script access denied");

class City 
{
    public int $city_id;
    public string $city_name;
    public string $deleted;
    public string $date_updated;
    public string $date_deleted;
}