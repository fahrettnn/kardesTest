<?php
namespace Settings\Services;

use App\Core\Http\Response;
use App\Core\Models\Security;
use Settings\Services\ISetting;


defined('ROOT') or die("Direct script access denied");

/**
 * ApiServices
 */
class SettingService extends Response implements ISetting
{

    public function mailSetting($putData)
    {
        $host      = Security::SecurityCode($putData["smtp_host"]);
        $port      = Security::SecurityCode($putData["smtp_port"]);
        $securty   = Security::SecurityCode($putData["smtp_securty"]);
        $email     = Security::SecurityCode($putData["smtp_email"]);
        $password  = Security::SecurityCode($putData["smtp_password"]);

        $mailValidate = $this->mailValidate($host,$port,$email,$password);
        if($mailValidate)
            return $mailValidate;

        $envPath = realpath('.') . DIRECTORY_SEPARATOR . '.env'; // .env dosyasının yolunu belirleyin

        if (file_exists($envPath) && is_writable($envPath)) {
            $envContent = file_get_contents($envPath);
    
            $envContent = preg_replace('/^SMTP_HOST=.*$/m', "SMTP_HOST='$host'", $envContent);
            $envContent = preg_replace('/^SMTP_PORT=.*$/m', "SMTP_PORT=$port", $envContent);
            $envContent = preg_replace('/^SMTP_SECURITY=.*$/m', "SMTP_SECURITY='$securty'", $envContent);
            $envContent = preg_replace('/^SMTP_EMAIL=.*$/m', "SMTP_EMAIL='$email'", $envContent);
            $envContent = preg_replace('/^SMTP_PASSWORD=.*$/m', "SMTP_PASSWORD='$password'", $envContent);
    
            if (file_put_contents($envPath, $envContent) !== false)
                return $this->success(__lang("mail_settings_updated_successfully"))->send();
            else 
                return $this->error(__lang("env_file_write_failed"))->send();
        } else
            return $this->error(__lang("something_went_wrong_try_again_later"))->send();

    }

    private function mailValidate($smtpHost,$smtp_port,$smtp_email,$smtp_password)
    {
        if(empty($smtpHost))
            return $this->info(__lang("smtp_host_cannot_be_empty"))->send();
        if(empty($smtp_port))
            return $this->info(__lang("smtp_port_cannot_be_empty"))->send();
        if(empty($smtp_email))
            return $this->info(__lang("smtp_email_cannot_be_empty"))->send();
        if(empty($smtp_password))
            return $this->info(__lang("smtp_password_cannot_be_empty"))->send();

        return false;
    }
    
    private function sendMailControl($smtp_host, $smtp_port, $smtp_email, $smtp_password,$smtp_securty)
    {
        $imageUrl = ROOT."/uploads/images/logo.svg";
        $subject = "TEST EMAİL";
        $message = '
        <!DOCTYPE html><html><head><meta charset="UTF-8">
            <style> body { font-family: Arial, sans-serif; }
                .container { width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .header { text-align: center; margin-bottom: 20px; }
                .header img { max-width: 150px; }
                .content { font-size: 16px; line-height: 1.5; }
                .footer { margin-top: 20px; font-size: 14px; color: #555;  text-align: center; }
            </style> </head> <body> <div class="container"><div class="header"> <img src="'.$imageUrl.'"></div>
                <div class="content">
                    <p>Merhaba, Kardeş Sondajcılık Yetkilisi</p>
                    <p>Bu Mail Test Mailidir</strong></p>
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
        $mailerFactory = new \App\Core\Models\MailSend($smtp_host, $smtp_port, $smtp_email, $smtp_password,$smtp_securty);
        $mailerType = 'phpmailer';
        $mailer     = $mailerFactory->createMailer($mailerType, $smtp_email, $subject, $message);
        if($mailer->send())
            return true;
        else
            return false;
    }
}