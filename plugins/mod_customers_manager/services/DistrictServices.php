<?php
namespace Mod\Customers\Services;

use App\Core\Http\Response;
use App\Core\Models\Session;
use Mod\Customers\Models\DistrictModel;
use Mod\Customers\Services\IDistrict;


defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class DistrictService extends Response implements IDistrictService
{
    private $model;
    private $session;

    public function __construct()
    {
        $this->model    = new DistrictModel;
        $this->session  = new Session;

        if(!$this->session->is_logged_in())
            return $this->errorResponse('Yetkisiz Erişim',401);
    }

    public function getCityIdDiscritList($cityId)
    {
        $getCityList = $this->model->getListCityIdDisctricts($cityId);
        if($getCityList)
            return $this->success("Belirtilen Şehire ait İlçeler Başarıyla Listelendi",$getCityList)->send();

        return $this->info("Belirtilen Şehire ait İlçe Bulunamadı","info",404)->send();
    }

    public function errorResponse($errorMessage,$statusCode = 400) { return $this->error($errorMessage,$statusCode)->send(); }

}