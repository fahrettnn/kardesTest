<?php
namespace Mod\Customers\Services;

use Mod\Customers\Adress;

defined('ROOT') or die("Direct script access denied");

interface IAdressService
{
    public function getAdressList();
    public function getIdByAdress($adres_id);
}