<?php
namespace Mod\Adress\Models;

use \App\Core\Database\Model;

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

    public function getList()
    {
        $control = $this->table($this->table_name)->get();
        return $control ?: false;
    }

    public function getFirst($primary_key)
    {
        $control = $this->table($this->table_name)->where($this->primary_key,$primary_key)->first();
        return $control ?: false;
    }

    public function cityNameFirst($cityName)
    {
        $control = $this->table($this->table_name)->where("city_name",$cityName)->first();
        return $control ?: false;
    }

    public function cityNameIdFirst($cityName,$id)
    {
        $control = $this->table($this->table_name)->whereRaw("city_name=? AND id!=?",[$cityName,$id])->first();
        return $control ?: false;
    }

    public function addCity($data, $lastinsertId = false)
    {
        return $this->table($this->table_name)->addCreate($data,$lastinsertId);
    }

    public function updateCity($id,$data)
	{
		return $this->table($this->table_name)->where($this->primary_key,$id)->update($data);
	}
    
    public function deleteCity($id)
	{
		return $this->table($this->table_name)->where($this->primary_key,$id)->delete();
	}

    public function deleteDiscirt($cityId)
    {
		return $this->table("tbl_district")->where("city_id",$cityId)->delete();
    }
    
}