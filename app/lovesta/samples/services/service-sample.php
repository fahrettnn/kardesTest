<?php
namespace {PLUGIN_NAME}\Services;

use App\Core\Http\Response;
use App\Core\Models\Session;
use {PLUGIN_NAME}\Models\{CLASS_NAME};
use {PLUGIN_NAME}\Services\I{CLASS_NAME};

defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class {CLASS_NAME}Service extends Response implements I{CLASS_NAME}
{
    private $model;
    private $session;

    public function __construct(\{PLUGIN_NAME}\Models\{CLASS_NAME}Model $model)
    {
        $this->model = $model;
        $this->session = new Session;
    }

    public function getListService()
    { 
        $data = $this->model->getList();
        if($data)
            return $this->json(['data' => $data ,'message' => "Firma Listelendi",'status' => 'success'])->setStatusCode(200)->send();
        else 
            return $this->json(['data' => [],'message' => "Firma Listelendi",'status' => 'success'])->setStatusCode(200)->send();

    }

    public function getFirstService($id)
    { return $this->model->getFirst($id); }

    public function addCreateService({CLASS_NAME} ${CLASS_NAME},$lastinsertId = false)
    {
        $validate = $this->{CLASS_NAME}Validate(${CLASS_NAME});
        if($validate)
            return $validate;

        ${CLASS_NAME}Control = $this->model->get{CLASS_NAME}Name(${CLASS_NAME}->company_name);
        if(${CLASS_NAME}Control)
            return $this->json(['message' => "Firma Zaten Mevcut",'status' => 'warning'])->setStatusCode(409)->send();
        else
        {
            ${CLASS_NAME}Data = [
                "company_name"          => ${CLASS_NAME}->company_name,
                "company_tax_office"    => ${CLASS_NAME}->company_tax_office,
                "company_tax_number"    => ${CLASS_NAME}->company_tax_number,
                "company_insurance_number"    => ${CLASS_NAME}->company_insurance_number,
                "company_status"        => ${CLASS_NAME}->company_status,
                "add_user"              => json_encode($this->session->user(), JSON_UNESCAPED_UNICODE),
            ];
            $add = $this->model->add(${CLASS_NAME}Data,$lastinsertId);
            if($add)
                return $this->json(['message' => "Firma Başarıyla Eklendi",'status' => 'success'])->setStatusCode(201)->send();
            else
                return $this->json(['message' => "Firma Eklenirken Bir Hata Meydana Geldi",'status' => 'error'])->setStatusCode(500)->send();
        }
    }

    public function updateService({CLASS_NAME} ${CLASS_NAME})
    {
        $validate = $this->{CLASS_NAME}Validate(${CLASS_NAME});
        if($validate)
            return $validate;

        ${CLASS_NAME}Control = $this->model->getFirst(${CLASS_NAME}->id);
        if(!${CLASS_NAME}Control)
            return $this->json(['message' => "Kayıtlı Firma Mevcut Değil",'status' => 'error'])->setStatusCode(409)->send();
        else
        {
            ${CLASS_NAME}Control = $this->model->{CLASS_NAME}NameControl(${CLASS_NAME}->{CLASS_NAME}_name,${CLASS_NAME}->id);
            if(${CLASS_NAME}Control)
                return $this->json(['message' => "Firma Zaten Mevcut",'status' => 'warning'])->setStatusCode(400)->send();
            else
            {
                $xxxData = [
                    "xxx"    => $xxx->xxx,
                    "xxx"    => $xxx->xxx,
                    "xxx"    => $xxx->xxx,
                    "xxx"    => $xxx->xxx,
                    "xxx"    => $xxx->xxx,
                    "xxx"    => $xxx->xxx,
                    "last_modify_user"      => json_encode($this->session->user(), JSON_UNESCAPED_UNICODE),
                ];
                $update = $this->model->updateData($xxx->id,$xxxData);
                if($update)
                    return $this->json(['message' => "Firma Bilgileri Başarıyla Güncellendi",'status' => 'success'])->setStatusCode(200)->send();
                else
                    return $this->json(['message' => "Firma Bilgileri Güncellenirken Bir Hata Meydana Geldi",'status' => 'error'])->setStatusCode(500)->send();
            
            }
        }
    }

    public function deleteService($id)
    { 
        if(empty($id))
            return $this->json(['message' => "Geçersiz İstek",'status' => 'error'])->setStatusCode(400)->send();

        $delete = $this->model->deleteData($id);
        if($delete)
            return $this->json(['message' => "Firma Başarıyla Silindi",'status' => 'success'])->setStatusCode(200)->send();
        else
            return $this->json(['message' => "Firma Silinirken Bir Hata Meydana Geldi",'status' => 'error'])->setStatusCode(500)->send();
    }


    private function xxxValidate($xxx)
    {
        if(empty($xxx->xxx_name))
        {
            return $this->json(['message' => "Firma Adı Boş olamaz! Lütfen Firma Adı Giriniz.",'status' => 'info'])->setStatusCode(400)->send();
        }
        if(empty($xxx->xxx_tax_office))
            return $this->json(['message' => "Vergi Dairesi Boş olamaz! Lütfen Vergi Dairesi Giriniz.",'status' => 'info'])->setStatusCode(400)->send();

        if(empty($xxx->xxx_tax_number))
            return $this->json(['message' => "Vergi Numarası Boş olamaz! Lütfen Vergi Numarası Giriniz.",'status' => 'info'])->setStatusCode(400)->send();
        
        if(empty($xxx->xxx_insurance_number))
            return $this->json(['message' => "SGK Numarası Boş olamaz! Lütfen SGK Numarası Giriniz.",'status' => 'info'])->setStatusCode(400)->send();
        if(empty($xxx->xxx_status))
            return $this->json(['message' => "Lütfen Firma Durumu Seçiniz.",'status' => 'info'])->setStatusCode(400)->send();

        return false;
    }
}