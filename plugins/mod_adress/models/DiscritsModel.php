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
class DiscritsModel extends Model
{
    private $table_name = "tbl_district";
	private $primary_key = 'id';

    public function getList()
    {
        $control = $this->table($this->table_name)->get();
        return $control ?: false;
    }

    public function getCityIdDiscritsList($cityId)
    {
        $control = $this->table($this->table_name)->where("city_id",$cityId)->get();
        return $control ?: false;
    }

    public function getFirst($primary_key)
    {
        $control = $this->table($this->table_name)->where($this->primary_key,$primary_key)->first();
        return $control ?: false;
    }

    public function getCityFirst($cityId)
    {
        $control = $this->table("tbl_city")->where("id",$cityId)->first();
        return $control ?: false;
    }

    public function cityIdDiscritNameFirst($cityId,$districName)
    {
        $control = $this->table($this->table_name)->whereRaw("city_id=? AND district_name=?",[$cityId,$districName])->first();
        return $control ?: false;
    }

    public function getDisNameId($districName,$cityId,$districId)
    {
        $control = $this->table($this->table_name)->whereRaw("id!=? AND city_id=? AND district_name=?",[$districId,$cityId,$districName])->first();
        return $control ?: false;
    }


    public function addData($data, $lastinsertId = false)
    {
        return $this->table($this->table_name)->addCreate($data,$lastinsertId);
    }

    public function updateData($id,$data)
	{
		return $this->table($this->table_name)->where($this->primary_key,$id)->update($data);
	}
    
    public function deleteData($id)
	{
		return $this->table($this->table_name)->where($this->primary_key,$id)->delete();
	}
    
}