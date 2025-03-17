<?php
namespace App\Core\Models;

use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
defined('ROOT') or die("Direct script access denied");

class MailSend
{ 
    private $host;
    private $port;
    private $email;
    private $password;
    private $security = "";

    public function __construct($host, $port, $email, $password,$security = "")
    {
        $this->host         = $host;
        $this->port         = $port;
        $this->email        = $email;
        $this->password     = $password;
        $this->security     = $security;
    }

    public function createMailer($type, $email, $subject, $message)
    {
        switch ($type) {
            case 'phpmailer':
                return new PhpMailerService($this->host, $this->port, $this->email, $this->password, $email, $subject, $message,$this->security);
            case 'nette':
                return new NetteMailService($this->host, $this->port, $this->email, $this->password, $email, $subject, $message, $this->security);
            default:
                throw new \Exception('Geçersiz mailer türü: ' . $type);
        }
    }
}

interface MailSendInterface
{
    public function send(): bool;
}

class PhpMailerService implements MailSendInterface
{
    private $host;
    private $port;
    private $email;
    private $password;
    private $toEmail;
    private $subject;
    private $message;
    private $security = "";

    public function __construct($host, $port, $email, $password, $toEmail, $subject, $message,$security)
    {
        $this->host = $host;
        $this->port = $port;
        $this->email = $email;
        $this->password = $password;
        $this->toEmail = $toEmail;
        $this->subject = $subject;
        $this->message = $message;
        $this->security = $security;
    }
    
    public function send(): bool
    {
        $MailGonder = new PHPMailer(true);
        try {
            //$MailGonder->SMTPDebug = 0;
            $MailGonder->isSMTP();
            $MailGonder->Host = $this->host;
            $MailGonder->SMTPAuth = true;
            $MailGonder->CharSet = "UTF-8";
            $MailGonder->Username = $this->email;
            $MailGonder->Password = $this->password;
            $MailGonder->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $MailGonder->Port = $this->port;


            $MailGonder->setFrom($this->email);
            $MailGonder->addAddress($this->toEmail);
            $MailGonder->addReplyTo($this->email);
            $MailGonder->isHTML(true);
            $MailGonder->Subject = $this->subject;
            $MailGonder->MsgHTML($this->message);
            $MailGonder->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error: " . $MailGonder->ErrorInfo);
            return false;
        }
    }


}

class NetteMailService implements MailSendInterface
{
    private $host;
    private $port;
    private $email;
    private $password;
    private $toEmail;
    private $subject;
    private $message;
    private $security;

    public function __construct($host, $port, $email, $password, $toEmail, $subject, $message, $security)
    {
        $this->host = $host;
        $this->port = $port;
        $this->email = $email;
        $this->password = $password;
        $this->toEmail = $toEmail;
        $this->subject = $subject;
        $this->message = $message;
        $this->security = $security;
    }
    
    public function send(): bool
    {
        try {
            $mail = new Message;
            $mail->setFrom($this->email)
                 ->addTo($this->toEmail)
                 ->setSubject($this->subject)
                 ->setHtmlBody($this->message);
            $mailer = new SmtpMailer($this->host,$this->email,$this->password,$this->port,$this->security);
            $mailer->send($mail);
            return true;
        } catch (\Exception $e) {
            error_log("Nette Mail Error: " . $e->getMessage());
            return false;
        }
    }
}