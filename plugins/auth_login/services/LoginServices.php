<?php
namespace Auth\Login\Services;

use App\Core\Http\Response;
use App\Core\JWTService;
use App\Core\Models\Security;
use App\Core\Models\Session;
use Auth\Login\Models\Login;
use Auth\Login\Models\LoginModel;
use Auth\Login\Services\ILogin;
use Google\Service\MyBusinessVerifications\EmailVerificationData;

defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class LoginService extends Response implements ILoginService
{
    private $model;
    private $session;
    private $jwtService;

    public function __construct()
    {
        $this->model        = new LoginModel;
        $this->session      = new Session;
        $this->jwtService   = new JWTService;
    }

    public function loginService(array $loginData)
    {
        $userEmail = Security::SecurityCode($loginData["email"]);
        $userPassw = Security::SecurityCode($loginData["password"]);
        $loginCsrf = Security::SecurityCode($loginData["login"]);

        $validate = $this->loginValidate($userEmail,$userPassw,$loginCsrf);
        if($validate)
            return $validate;

        $password = Security::securityPassword($userPassw);
        $userControl = $this->model->userControlModel($userEmail,$password);
        if(!$userControl)
            return $this->info('Hatalı E-Posta veya parola. Lütfen Yeniden Deneyiniz');
        else
        {
            return $this->sessionTokenUpdate($userEmail);
        }
    }

    public function sessionTokenUpdate($email,$session_token = null)
    {
        $session_token = Security::generateToken('login_token');
        $result = $this->model->sessionTokenUpdate($email,$session_token);
        if($result)
        {
            $data    = $this->model->getSessionControl($session_token);
            $session = new Session;
            $session->set("User", $data);
            return $this->success("Giriş Başarılı Yönlendiriliyorsunuz")->send();
        }
        else{
            return $this->error('Bir hata oluştu. Lütfen daha sonra tekrar dene.')->send();
        }
    }

    private function loginValidate($email,$pass,$csrf)
    {
        if(empty($email))
            return $this->info("E-Posta Adresi Boş Olamaz")->send();
        if (!validateEmail($email))
            return $this->info("Lütfen Geçerli Bir E-Posta Adresi Giriniz.")->send();
        if(empty($pass))
            return $this->info("Parola Boş Olamaz")->send();
        if($_SESSION[$csrf])
            return $this->error("Bir Hata Oluştu. Lütfen Sayfayı Yenileyerek tekrar deneyiniz")->send();
    
        return false;
    }
    
}