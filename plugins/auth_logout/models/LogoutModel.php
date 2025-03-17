<?php
namespace Auth\Logout\Models;

use \App\Core\Database\Model;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Respect\Validation\Validator as v;

defined('ROOT') or die("Direct script access denied");

/**
 * {ApiModel} class
 */
class LogoutModel extends Model
{
    private $table_name = "your_table_name";
	private $primary_key = 'your_table_id';

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

    public function add($data, $lastinsertId = false)
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