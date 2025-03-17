<?php
namespace Mod\Customers\Services;

use App\Core\Http\Response;
use App\Core\Models\Session;
use Mod\Customers\Models\CityModel;

defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class CityService extends Response implements ICityService
{
    private $cityModel;
    private $session;

    public function __construct()
    {
        $this->cityModel = new CityModel;
        $this->session   = new Session;

        if(!$this->session->is_logged_in())
            return $this->errorResponse('Yetkisiz Erişim',401);
    }

    /** Tüm Şehirleri Listele */
    public function getListService()
    {
        $getCityList = $this->cityModel->getListCity();
        if($getCityList)
            return $this->success("Tüm Aktif Şehirler Başarıyla Listelendi",$getCityList)->send();

        return $this->info("Aktif Şehir Bulunamadı","info",404)->send();
    }
    
    /** Tek Şehiri Listele */
    public function getCityFirst($cityId)
    {
        if(empty($cityId))
            return $this->info("Şehir Id Boş olamaz")->send();

        $getDistrictFirst = $this->cityModel->getFirstCity($cityId);
        if($getDistrictFirst)
            return $this->success("Şehir Başarıyla listelendi",$getDistrictFirst)->send();

        return $this->info("Id ye ait Şehir Bulunamadı","info",404)->send();
    }

    public function errorResponse($errorMessage,$statusCode = 400) { return $this->error($errorMessage,$statusCode)->send(); }
}