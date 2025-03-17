<?php
namespace Mod\Customers\Services;

defined('ROOT') or die("Direct script access denied");

interface IDistrictService
{
    public function getCityIdDiscritList($cityId);
}