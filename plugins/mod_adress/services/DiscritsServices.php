<?php
namespace Mod\Adress\Services;

use App\Core\Http\Response;
use App\Core\Models\Security;
use App\Core\Models\Session;
use Mod\Adress\Models\Discrits;
use Mod\Adress\Models\DiscritsModel;

use function PHPUnit\Framework\returnSelf;

defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class DiscritsService extends Response implements IDiscritsService
{
    private $model;
    private $session;

    public function __construct()
    {
        $this->model = new DiscritsModel;
        $this->session = new Session;

        if(!$this->session->is_logged_in())
            return $this->errorResponse('Yetkisiz Erişim',401);
    }

    public function getListService()
    {
        $data = $this->model->getList();
        if($data)
            return $this->success("Tüm İlçeler Listelendi",$data)->send();
        else 
            return $this->success("Tüm İlçeler Listelendi",[])->send();
    }

    public function getCityIdAllListService($cityId)
    {
        $data = $this->model->getCityIdDiscritsList($cityId);
        if($data)
            return $this->success("İl Idsine göre Tüm İlçeler Listelendi",$data)->send();
        else 
            return $this->success("İl Idsine göre Tüm İlçeler Listelendi",[])->send();
    }

    public function addService(Discrits $discrit)
    {
        $validate = $this->validateDiscrit($discrit);
        if($validate)
            return $validate;
        
        $cityId = Security::SecurityCode($discrit->city_id);
        $discritName = Security::SecurityCode($discrit->district_name);
        $discritStatus = Security::SecurityCode($discrit->status);

        $control = $this->model->cityIdDiscritNameFirst($cityId,$discritName);
        if($control)
            return $this->info("İlçe Zaten Mevcut")->send();
        else
        {
            $data = [
                "city_id"   => $cityId,
                "district_name"  => $discritName,
                "status"  => $discritStatus
            ];
            $add = $this->model->addData($data);
            if($add)
                return $this->success("İlçe Başarıyla Eklendi")->send();
            else
                return $this->error(__lang("something_went_wrong_try_again_later"))->send();
        }
    }

    public function updateService(Discrits $discrit)
    {
        if(empty($discrit->id))
            return $this->error("Hatalı İstek Geçersiz parametre",404)->send();

        $validate = $this->validateDiscrit($discrit);
        if($validate)
            return $validate;

        $discritControl = $this->model->getFirst($discrit->id);
        if(!$discritControl)
            return $this->info("Geçersiz İlçe","404")->send();
        else
        {
            $cityControl = $this->model->getCityFirst($discrit->city_id);
            if(!$cityControl)
                return $this->info("İl Geçersiz")->send();
            else
            {
                $updateNameCont = $this->model->getDisNameId($discrit->district_name,$discrit->city_id,$discrit->id);
                if($updateNameCont)
                    return $this->info("Bu İlçe Zaten Mevcut")->send();


                $updateData = [
                    "city_id" => $discrit->city_id,
                    "district_name" => $discrit->district_name,
                    "status"        => $discrit->status,
                    "date_updated"  => date('Y-m-d H:i:s')
                ];

                $update = $this->model->updateData($discrit->id,$updateData);
                if($update)
                    return $this->success("İlçe Başarıyla Güncellendi")->send();
                else
                    return $this->error(__lang("something_went_wrong_try_again_later"))->send();
            }
        }
    }

    public function deleteService($discritId)
    {
        if(empty($discritId))
            return $this->error("Hatalı İstek Geçersiz parametre",404)->send();

        $discritControl = $this->model->getFirst($discritId);
        if(!$discritControl)
            return $this->info("Geçersiz İlçe","404")->send();
        else
        {
            $delete = $this->model->deleteData($discritId);
            if($delete)
                return $this->success("İlçe Başarıyla Silindi")->send();
            else
                return $this->error(__lang("something_went_wrong_try_again_later"))->send();
        }
    }

    private function validateDiscrit(Discrits $discrit)
    {   
        if(empty($discrit->city_id))
            return $this->info("Lütfen İl Seçiniz")->send();

        if(empty($discrit->district_name))
            return $this->info("İlçe Adı Boş Olamaz")->send();

        if(empty($discrit->status))
            return $this->info("İlçe Durumu Seçiniz")->send();
        
        return false;
    }

    public function errorResponse($errorMessage,$statusCode = 400) { return $this->error($errorMessage,$statusCode)->send(); }
}