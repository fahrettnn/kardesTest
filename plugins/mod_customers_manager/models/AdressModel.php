<?php
namespace Mod\Customers\Models;

use \App\Core\Database\Model;
use App\Core\Models\Session;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Respect\Validation\Validator as v;

defined('ROOT') or die("Direct script access denied");

/**
 * {ApiModel} class
 */
class AdressModel extends Model
{
    private $table_name = "tbl_adress";
	private $primary_key = 'adress_id';
    private $session;

    public function __construct() { $this->session = new Session;  }

    public function getListAdress()
    {
        $control = $this->table($this->table_name)->get();
        return $control ?: false;
    }

    public function getFirst($primary_key)
    {
        $control = $this->table($this->table_name)->where($this->primary_key,$primary_key)->first();
        return $control ?: false;
    }


    public function addAdress($data, $lastinsertId = false)
    {
        $this->logAdressAdd($data);
        $add = $this->table($this->table_name)->addCreate($data,$lastinsertId);
        return $add ?: false;
    }

    public function updateAdress($data)
	{
        $this->logAdressEdit($data);
		$update = $this->table($this->table_name)->where("adress_id",$data['adress_id'])->update($data);
        return $update ?: false;
	}
    
    public function deleteAdress($adress_id)
	{
        $this->logAdressDelete($adress_id);
		$del = $this->table($this->table_name)->where($this->primary_key,$adress_id)->delete();
        return $del ?: false;
	}

    /** loglama İşlemleri */
    private function logAdressAdd($data): void
    {
        $logger = new Logger('adress');
        $logger->pushHandler(new StreamHandler(realpath('.') . '/dev-tools/logs/adress/addAdress.log'));
        $logger->info(
            'Adres Ekleme Bilgileri',
            [
                "Eklenen Adres Bilgisi"     => $data,
                "Ekleyen Kişi Ip Adresi"    => $_SERVER['REMOTE_ADDR'],
                "Ekleyen Kişi Detayı"       => $this->session->user()
            ]
        );
    }
    private function logAdressEdit($data): void
    {
        $logger = new Logger('adress');
        $logger->pushHandler(new StreamHandler(realpath('.') . '/dev-tools/logs/adress/updateAdress.log'));
        $logger->info(
            'Adres Güncelleme Bilgileri',
            [
                "Eklenen Adres Bilgisi"     => $data,
                "Ekleyen Kişi Ip Adresi"    => $_SERVER['REMOTE_ADDR'],
                "Ekleyen Kişi Detayı"       => $this->session->user()
            ]
        );
    }
    private function logAdressDelete($adress_id): void
    {
        $logger = new Logger('adress');
        $logger->pushHandler(new StreamHandler(realpath('.') . '/dev-tools/logs/adress/deleteAdress.log'));
        $logger->error(
            'Adres Silme Bilgileri',
            [
                "Silinen Adres Id"          => $adress_id,
                "Ekleyen Kişi Ip Adresi"    => $_SERVER['REMOTE_ADDR'],
                "Ekleyen Kişi Detayı"       => $this->session->user()
            ]
        );
    }
}