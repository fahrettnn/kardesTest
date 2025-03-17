<?php

namespace Auth\ProfileEdit\Controllers;

use App\Core\Http\Request;
use App\Core\Models\Security;
use Auth\ProfileEdit\Models\Profile;
use Auth\ProfileEdit\Services\ProfileEditService;

defined('ROOT') or die("Direct script access denied");

class ProfileEditController extends Request
{
    private $services;
    
    public function __construct()
    {
        $this->services = new ProfileEditService(new \Auth\ProfileEdit\Models\ProfileEditModel());
    }

    public function profileEdit()
    {
        $postData  = $this->post();
        $filedata = $this->files();

        $profile = new Profile;
        $profile->userFirstname       = Security::SecurityCode($postData['firstname']);
        $profile->userLastname        = Security::SecurityCode($postData['lastname']);
        $profile->userPhone           = Security::SecurityCode($postData['phone']);
        $profile->userPassword        = Security::SecurityCode($postData['avail_password']);
        $profile->userNewPassword     = Security::SecurityCode($postData['new_password']);
        $profile->userNewPasswordRe   = Security::SecurityCode($postData['new_password_again']);
        if (isset($filedata['userImage']) && $filedata['userImage']['error'] == UPLOAD_ERR_OK) {
            $profile->userImg = $filedata['userImage'];
        } else {
            $profile->userImg = ''; // Dosya yüklenmediyse boş olarak ayarla
        }        
        return $this->services->updateProfile($profile);
    }

}