<?php
namespace Mod\Customers\Models;

use \App\Core\Database\Model;
use App\Core\Models\Session;
use Mod\Customers\City;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Respect\Validation\Validator as v;

defined('ROOT') or die("Direct script access denied");

/**
 * {ApiModel} class
 */
class CityModel extends Model
{
    private $table_name = "tbl_city";
	private $primary_key = 'id';
    
    public function getListCity()
    {
        $control = $this->table($this->table_name)->where('status',1)->get();
        return $control ?: false;
    }

    public function getFirstCity($city_id)
    {
        $control = $this->table($this->table_name)->where($this->primary_key,$city_id)->first();
        return $control ?: false;
    }


    
}