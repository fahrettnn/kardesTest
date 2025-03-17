<?php
namespace Auth\Reset\Password\Services;

use App\Core\Http\Response;
use App\Core\Models\Security;
use App\Core\Models\Session;
use Auth\Reset\Password\Models\ResetPassword;
use Auth\Reset\Password\Models\ResetPasswordModel;
use Auth\Reset\Password\Services\IResetPassword;

defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class ResetPasswordService extends Response implements IResetPassword
{
    private $model;
    private $session;

    public function __construct()
    {
        $this->model    = new ResetPasswordModel;
        $this->session  = new Session;
    }

    public function resetPasswordCode($postData)
    { 
        $email = Security::SecurityCode($postData["email"]);
        
        $validate = $this->emailValidate($email);
        if($validate)
            return $validate;

        $control = $this->model->userEmailControl($email);
        if(!$control)
            return $this->success("E-Postan kutunuzu kontrol ediniz.")->send();
        else
        {
            $codeGen = Security::generateToken("code");
            $codeSuccessEmail = $this->model->codeSuccessEmail($email);
            if($codeSuccessEmail)
            {
                // Kullanıcının Zaten geçerli bir kodu vardır mevcut olanı pasif hale getir.
                $updateCodeEmail = $this->model->updateCodePasive($email);
                if($updateCodeEmail)
                {
                    $codeAdd = $this->codeAddAndMailSend($email,$codeGen);
                    if($codeAdd)
                        return $this->success('Parola Sıfırlama Bağlantısı Başarıyla Mail Adresinize Gönderildi')->send();
                    else
                        return $this->error('1 - Bir hata oluştu. Lütfen daha sonra tekrar deneyin.',500)->send();
                    
                }else
                    return $this->error('2 - Bir hata oluştu. Lütfen daha sonra tekrar deneyin.',500)->send();
            }else
            {
                $codeAdd = $this->codeAddAndMailSend($email,$codeGen);
                if($codeAdd)
                    return $this->success('Parola Sıfırlama Bağlantısı Başarıyla Mail Adresinize Gönderildi')->send();
                else
                    return $this->error('1 - 1- Bir hata oluştu. Lütfen daha sonra tekrar deneyin.',500)->send();
            }
        }

    }

    private function codeAddAndMailSend($email,$code)
    {
        // Code Ekle ve sonra Mail Post Çağır
        $codeData = [
            "email"  => $email,
            "reset_code"        => $code,
            "expires_at"        => date('Y-m-d H:i:s', strtotime('+10 hours')),
            "used_at"           => date('Y-m-d H:i:s'),
            "ip_address"        => $_SERVER['REMOTE_ADDR'],
            "user_agent"        => $_SERVER['HTTP_USER_AGENT'],
            "code_status"       => 1,
            "attempts"          => 0,
            "last_attempt_at"   => 0
        ];
        $addCode = $this->model->addCode($codeData);
        if($addCode)
        {
            $mailSend = $this->emailPost($email,$code);
            if($mailSend)
                return true;
            else
                return false;
        }
        else
            return false;
    }

    private function emailPost($email,$code)
    {
        $smtp_host      = $_ENV['SMTP_HOST'];
        $smtp_port      = $_ENV['SMTP_PORT'];
        $smtp_email     = $_ENV['SMTP_EMAIL'];
        $smtp_password  = $_ENV['SMTP_PASSWORD'];
        $smtp_securty   = $_ENV['SMTP_SECURITY'];
        // Mail Adresine oluşturulan bağlantıyı gönder ve url verify olacak şekilde ayarla
        $imageUrl = ROOT."/public/uploads/images/logo.svg";
        $subject = "Parola Sıfırlama İsteği | ". APP_NAME ;
        $message = '
        <!DOCTYPE html><html><head><meta charset="UTF-8">
            <style> body { font-family: Arial, sans-serif; }
                .container { width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .header { text-align: center; margin-bottom: 20px; }
                .header img { max-width: 150px; }
                .content { font-size: 16px; line-height: 1.5; }
                .footer { margin-top: 20px; font-size: 14px; color: #555;  text-align: center; }
                .btn {
                    display: inline-block;
                    padding: 12px 24px;
                    background-color: #007bff;
                    color: white;
                    font-size: 16px;
                    text-decoration: none;
                    border-radius: 8px;
                    transition: background 0.3s ease, transform 0.2s ease;
                    font-weight: bold;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }
                .btn:hover {
                    background-color: #0056b3;
                    transform: translateY(-2px);
                }
                .btn:active {
                    background-color: #004494;
                    transform: translateY(0);
                }
            </style> </head> <body> <div class="container"><div class="header"> <img src="'.$imageUrl.'"></div>
                <div class="content">
                    <p>Merhaba, Kardeş Sondajcılık Yetkilisi</p>
                    <p>Yeni Parolanızı oluşturmak için lütfen aşağıdaki bağlantıya tıklayınız</strong></p>
                    <a class="btn" href="'.ROOT.'/auth/reset-password/verify?email='.$email.'&code='.$code.'" target="_blank">Parola Sıfırla</a>
                    <p>Eğer bu isteği siz talep etmediyseniz, lütfen yöneticiniz ile iletişime geçiniz.</p>
                    <p>İyi günler dileriz.</p>
                </div>
                <div class="footer">
                    <p>Kardeş Sondajcılık Araştırma Taahhüt ve Tic.Ltd.Şti.<br>
                    Küçükesat, Başak Sk. No:25/3, 06660 Çankaya/Ankara<br>
                    (0312) 418 43 75<br>
                    info@kardessondajcilik.com.tr</p>
                </div>
            </div>
        </body>
        </html>';
        $mailerFactory = new \App\Core\Models\MailSend($smtp_host ,$smtp_port ,$smtp_email ,$smtp_password ,$smtp_securty );
        $mailerType = 'nette';
        $mailer     = $mailerFactory->createMailer($mailerType, $email, $subject, $message);
        if ($mailer->send())
            return true;
        else
            return false;
    }




    public function resetPasswordVerify($putData)
    { 
        
        $data = $this->model->updateData($putData,$putData);
        if($data)
            return $this->json(['data' => $data ,'message' => "Firma Listelendi",'status' => 'success'])->setStatusCode(200)->send();
        else 
            return $this->json(['data' => [],'message' => "Firma Listelendi",'status' => 'success'])->setStatusCode(200)->send();

    }

    private function emailValidate($email)
    {
        if(empty($email))
            return $this->info('E-Posta Adresi Boş Olamaz')->send();

        if(!validateEmail($email))
            return $this->info('Geçerli Bir E-Posta Adresi Giriniz',"warning")->send();

        return false;
    }
}