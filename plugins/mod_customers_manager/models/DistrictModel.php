<?php
namespace Mod\Customers\Models;

use \App\Core\Database\Model;
use App\Core\Models\Security;
use Mod\Customers\Districts;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Respect\Validation\Validator as v;

defined('ROOT') or die("Direct script access denied");

/**
 * {ApiModel} class
 */
class DistrictModel extends Model
{
    private $table_name = "tbl_district";

    public function getListCityIdDisctricts($cityId)
    {
        $control = $this->table($this->table_name)->whereRaw("city_id=? AND status=?",[$cityId,1])->get();
        return $control ?: false;
    }
    
}