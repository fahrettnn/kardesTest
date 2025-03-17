<?php
namespace Mod\Customers\Services;

defined('ROOT') or die("Direct script access denied");

interface ICityService
{
    public function getListService();
    public function getCityFirst($cityId);
}