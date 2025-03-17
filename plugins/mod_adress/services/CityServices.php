<?php
namespace Mod\Adress\Services;

use App\Core\Http\Response;
use App\Core\Models\Security;
use App\Core\Models\Session;
use Mod\Adress\Models\City;
use Mod\Adress\Models\CityModel;

use function PHPUnit\Framework\returnSelf;

defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class CityService extends Response implements ICityService
{
    private $model;
    private $session;

    public function __construct()
    {
        $this->model    = new CityModel;
        $this->session  = new Session;

        if(!$this->session->is_logged_in())
            return $this->errorResponse('Yetkisiz Erişim',401);

    }

    public function getListService()
    {
        $data = $this->model->getList();
        if($data)
            return $this->success("İller Listelendi",$data)->send();
        else 
            return $this->success("İller Listelendi",[])->send();
    }

    public function getCityService($cityId)
    {
        if(empty($cityId))
            return $this->info("İl İd Boş Hatalı İstek")->send();

        $data = $this->model->getFirst($cityId);
        if($data)
            return $this->success("İl Listelendi",$data)->send();
        else 
            return $this->success("İl Bulunamadı",[])->send();
    }

    public function addCityService(City $city)
    {
        $validate = $this->validateCity($city);
        if($validate)
            return $validate;
    
        $cityName = Security::SecurityCode($city->city_name);
        $cityStatus = Security::SecurityCode($city->status);

        $control = $this->model->cityNameFirst($cityName);
        if($control)
            return $this->info("İl Zaten Mevcut")->send();
        else
        {
            $data = [
                "city_name"  => $cityName,
                "status"  => $cityStatus
            ];
            $add = $this->model->addCity($data);
            if($add)
                return $this->success("İl Başarıyla Eklendi")->send();
            else
                return $this->error(__lang("something_went_wrong_try_again_later"))->send();
        }
    }

    public function editCityService(City $city)
    {
        $validate = $this->validateCity($city);
        if($validate)
            return $validate;
        
        $cityId     = Security::SecurityCode($city->id);
        $cityName   = Security::SecurityCode($city->city_name);
        $cityStatus = Security::SecurityCode($city->status);

        $control = $this->model->getFirst($cityId);
        if(!$control)
            return $this->info("İl Mevcut Değil")->send();
        else
        {
            $controlName = $this->model->cityNameIdFirst($cityName,$cityId);
            if($controlName)
                return $this->info("İl Zaten Mevcut")->send(); 
            else
            {
                $data = [
                    "city_name"  => $cityName,
                    "status"     => $cityStatus,
                    "date_updated"  => date('Y-m-d H:i:s')
                ];
                $add = $this->model->updateCity($cityId,$data);
                if($add)
                    return $this->success("İl Başarıyla Güncellendi")->send();
                else
                    return $this->error(__lang("something_went_wrong_try_again_later"))->send();
            }
        }
    }
    
    public function deleteCityService($cityId)
    {
        if(empty($cityId))
            return $this->error("Hatalı İstek Geçersiz parametre",404)->send();

        $cityControl = $this->model->getFirst($cityId);
        if(!$cityControl)
            return $this->info("Geçersiz İl","404")->send();
        else
        {
            $delete = $this->model->deleteCity($cityId);
            if(!$delete)
                return $this->error(__lang("something_went_wrong_try_again_later"))->send();
            else
            {
                $discritDelete = $this->model->deleteDiscirt($cityId);
                if($discritDelete)
                    return $this->success("İl Başarıyla Silindi")->send();
                else
                    return $this->error(__lang("something_went_wrong_try_again_later"))->send();
            }
                
        }
    }
    
    private function validateCity(City $city)
    {   
        if(empty($city->city_name))
            return $this->info("İl Adı Boş Olamaz")->send();

        if(empty($city->status))
            return $this->info("İl Durumu Seçiniz")->send();
        
        return false;
    }

    public function errorResponse($errorMessage,$statusCode = 400) { return $this->error($errorMessage,$statusCode)->send(); }

}