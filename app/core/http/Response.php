<?php
namespace App\Core\Http;

class Response
{
    private $statusCodes = [
        200 => 'OK',
        201 => 'Created',
        204 => 'No Content',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
    ];

    private $headers = [];
    private $statusCode = 200;
    private $body;
    private $acceptFormat;

    // Constructor, isteği analiz edip formatı belirler
    public function __construct()
    {
        // İstekten gelen 'Accept' başlığını alıyoruz, eğer yoksa varsayılan olarak 'application/json' kullanıyoruz
        $this->acceptFormat = $_SERVER['HTTP_ACCEPT'] ?? 'application/json';
    }

    // Durum kodunu ayarlamak
    public function setStatusCode(int $code): self
    {
        if (array_key_exists($code, $this->statusCodes)) {
            $this->statusCode = $code;
        } else {
            throw new \InvalidArgumentException("Invalid status code: {$code}");
        }
        return $this;
    }

    // Başlık eklemek
    public function setHeader(string $key, string $value): self
    {
        // Aynı başlık ekleniyorsa güncelleme yap
        $this->headers[$key] = $value;
        return $this;
    }

    // Yanıt gövdesini ayarlamak
    public function setBody($body): self
    {
        $this->body = $body;
        return $this;
    }

    // JSON yanıtı ayarlamak
    public function json($data, int $statusCode = 200): self
    {
        $this->setHeader('Content-Type', 'application/json');
        $this->setStatusCode($statusCode);
        $this->setBody(json_encode($data, JSON_PRETTY_PRINT));
        return $this;
    }

    // XML yanıtı ayarlamak
    public function xml($data, int $statusCode = 200): self
    {
        $this->setHeader('Content-Type', 'application/xml');
        $this->setStatusCode($statusCode);
        $xmlData = $this->toXml($data);
        $this->setBody($xmlData);
        return $this;
    }

    // PHP dizisini XML formatına dönüştürmek
    private function toXml($data, \SimpleXMLElement $xmlData = null): string
    {
        if ($xmlData === null) {
            $xmlData = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root></root>');
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->toXml($value, $xmlData->addChild($key));
            } else {
                $xmlData->addChild($key, $value);
            }
        }

        return $xmlData->asXML();
    }

    // Gelen 'Accept' başlığına göre uygun formatta yanıt döndürmek
    public function sendResponse($data): void
    {
        // Eğer istemci JSON istiyorsa, JSON formatında döndür
        if (strpos($this->acceptFormat, 'application/json') !== false) {
            $this->json($data);
        }
        // Eğer istemci XML istiyorsa, XML formatında döndür
        elseif (strpos($this->acceptFormat, 'application/xml') !== false) {
            $this->xml($data);
        }
        // Eğer başka bir format belirtilmemişse, varsayılan olarak JSON döndür
        else {
            $this->json($data);
        }

        $this->send();
    }

    // Yanıtı gönder
    public function send(bool $terminate = true): void
    {
        http_response_code($this->statusCode);
        
        // Başlıkları gönder
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }

        // Yanıt gövdesini gönder
        if ($this->body !== null) {
            echo $this->body;
        }

        if ($terminate) {
            exit; // İsteğe bağlı olarak işlemi sonlandırabiliriz
        }
    }

    // Yönlendirme (redirect)
    public function redirect(string $url, int $statusCode = 302): void
    {
        $this->setStatusCode($statusCode);
        $this->setHeader('Location', $url);
        $this->send();
    }

    // Hata mesajı göndermek
    public function info(string $message, string $status = "info", int $statusCode = 400): self
    {
        return $this->json([
            'message'       => $message,
            'status'        => $status,
            'status_code'   => $statusCode
        ], $statusCode);
    }

    public function error(string $message, int $statusCode = 500): self
    {
        return $this->json([
            'message' => $message,
            'status' => "error",
            'status_code'   => $statusCode
        ], $statusCode);
    }

    // Başarı mesajı göndermek
    public function success(string $message,  $data = [], int $statusCode = 200): self
    {
        return $this->json([
            'message' => $message,
            'data'   => $data,
            'status' => "success",
            'status_code' => $statusCode
        ], $statusCode);
    }

    // Dosya indirme işlevi
    public function downloadFile(string $filePath, string $fileName = null): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: {$filePath}", 404);
        }

        $fileSize = filesize($filePath);
        $fileName = $fileName ?? basename($filePath);

        // Dosya başlıkları
        $this->setHeader('Content-Type', 'application/octet-stream');
        $this->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        $this->setHeader('Content-Length', (string)$fileSize);

        // Dosya içeriğini oku ve gönder
        readfile($filePath);
        exit;
    }
}
