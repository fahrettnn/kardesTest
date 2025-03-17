<?php
namespace Mod\Customers\Services;

use App\Core\Http\Response;
use Mod\Customers\Adress;
use Mod\Customers\Models\AdressModel;
use Mod\Customers\Services\IAdressService;


defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class AdressService extends Response implements IAdressService
{
    private $model;

    public function __construct()
    {
        $this->model = new AdressModel;
    }

    /** API return */
    public function getAdressList()
    {
        $adressData = $this->model->getListAdress();
        if($adressData)
            return $this->success(__lang("all_address_records_listed"),$adressData)->send();

        return $this->info(__lang("address_record_not_found"),"info",404)->send();
    }

    public function adressFirst()
    {
        $adressData = $this->model->getListAdress();
        if($adressData)
            return $this->success(__lang("address_records_listed"),$adressData)->send();

        return $this->info(__lang("address_record_not_found"),"info",404)->send();
    }

    /** Array return */
    public function getIdByAdress($adresId)
    {
        $adress = $this->model->getFirst($adresId);
        return $adress ?: false;
    }

}