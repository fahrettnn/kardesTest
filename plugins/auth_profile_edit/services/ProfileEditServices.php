<?php
namespace Auth\ProfileEdit\Services;

use App\Core\Http\Response;
use App\Core\Models\Security;
use App\Core\Models\Session;
use Auth\ProfileEdit\Models\Profile;
use Auth\ProfileEdit\Services\IProfileEdit;

defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class ProfileEditService extends Response implements IProfileEdit
{
    private $model;
    private $session;

    public function __construct(\Auth\ProfileEdit\Models\ProfileEditModel $model)
    {
        $this->model = $model;
        $this->session = new Session;
    }

    public function updateProfile(Profile $profile)
    {
        $session = new Session;
        $managerdata = $session->getUser();
        if (isset($managerdata->user_email)) 
        {
            $editValidate = $this->editValidate($profile);
            if($editValidate)
                return $editValidate;

            if($profile->userPassword!='' && $managerdata->user_password == Security::securityPassword($profile->userPassword))
            {
                $passwordValidate = $this->passwordValidate($profile->userNewPassword,$profile->userNewPasswordRe);
                if($passwordValidate)
                    return $passwordValidate;

                $profile->userPassword = Security::securityPassword($profile->userNewPassword);
            }
            else if ($profile->userPassword!='' && $managerdata->user_password != Security::securityPassword($profile->userPassword)) {
                return $this->info("Mevcut parolanız uyuşmuyor")->send();
            }
            else if ($profile->userNewPassword!='' && $profile->userNewPasswordRe !='') {
                return $this->info("Parolalar uyuşmuyor")->send();
            }

            if (!empty($profile->userImg)) 
            {   
                if($managerdata->user_img != "test.jpg")
                    $removeImage = $managerdata->user_img;
                else
                    $removeImage = null;

                $file_path 	   	= "/public/uploads/images/profiles/";
                $NewImageNameG 	= NewImageNameGenerate();
                $ImageExtension  = pathinfo($profile->userImg["name"], PATHINFO_EXTENSION); // Dosya uzantısını almak için pathinfo kullanıyoruz
                Verot($profile->userImg, $NewImageNameG, $file_path, ["image/*"], null, null, $removeImage);
                $profile->userImg = $NewImageNameG . '.' . $ImageExtension;
            }

            $profile->userEmail = $managerdata->user_email;
            $update = $this->model->updateData($profile);
            if($update)
            {
                $control = $this->model->getFirst($managerdata->user_email);
                $session->auth($control);
                return $this->success("Profiliniz Başarıyla Güncellendi")->send();
            }
            else
                return $this->error("Bir hata oluştu. Lütfen daha sonra tekrar dene.")->send();
        }
    }


    private function editValidate($data)
    {
        if(!$this->session->is_logged_in())
            return $this->info('Yetkisiz Erişim',401)->send();

        if (empty($data->userFirstname))
            return $this->info("Adınız boş olamaz")->send();
        if (empty($data->userLastname))
            return $this->info("Soyadınız boş olamaz")->send();
        if (empty($data->userPhone))
            return $this->info("Telefon Numaranız boş olamaz")->send();

        return false;
    }

    public function passwordValidate($newpassword, $newpasswordrety)
    {
        if (empty($newpassword) || empty($newpasswordrety))
            return $this->info("Yeni Parola alanları boş olamaz")->send();

        if (strlen($newpassword) < 8)
            return $this->info("Parola en az 8 karakter uzunluğunda olmalıdır")->send(); // Parola uzunluğu 8 karakterden kısa

        if ($newpassword != $newpasswordrety)
            return $this->info("Parolalar uyuşmuyor")->send(); // Parolalar eşit değil

        return false;
    }
}