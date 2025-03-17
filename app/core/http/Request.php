<?php 

namespace App\Core\Http;

defined('ROOT') or die("Direct script access denied");

class Request
{
    private $putDataCache       = null;
    private $deleteDataCache    = null;
    public $upload_max_size     = 20;
    public $upload_folder       = 'uploads';
    public $upload_errors       = [];
    public $upload_error_code   = 0;
    public $upload_file_types   = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
    ];

    public function server($param): string
    {
        return $_SERVER[$param];
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function posted(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    // Ortak veri alma fonksiyonu
    private function getInputData(string $method, string $key = '', $default = '')
    {
        $data = [];
        switch (strtoupper($method)) {
            case 'POST':
                $data = $_POST;
                break;
            case 'GET':
                $data = $_GET;
                break;
            case 'PUT':
                if (is_null($this->putDataCache)) {
                    $this->parsePutData();
                }
                $data = $this->putDataCache;
                break;
            case 'DELETE':
                if (is_null($this->deleteDataCache)) {
                    $this->parseDeleteData();
                }
                $data = $this->deleteDataCache;
                break;
            default:
                break;
        }
        return $key ? ($data[$key] ?? $default) : $data;
    }

    // POST verisini almak
    public function post(string $key = '', string $default = ''): string|array
    {
        return $this->getInputData('POST', $key, $default);
    }

    // GET verisini almak
    public function get(string $key = '')
    {
        return $this->getInputData('GET', $key);
    }

    // PUT verisini almak
    public function put(string $key = '', string $default = ''): string|array
    {
        return $this->getInputData('PUT', $key, $default);
    }

    // DELETE verisini almak
    public function delete(string $key = '', string $default = ''): string|array
    {
        return $this->getInputData('DELETE', $key, $default);
    }

    private function parsePutData(): void
    {
        if ($this->method() !== 'PUT') {
            return;
        }

        $contentType = $this->server('CONTENT_TYPE') ?? '';
        if (str_contains($contentType, 'multipart/form-data')) {
            $boundary = substr($contentType, strpos($contentType, 'boundary=') + 9);
            $rawData = file_get_contents('php://input');
            $blocks = preg_split("/-+$boundary/", $rawData);
            array_pop($blocks);
            foreach ($blocks as $block) {
                if (empty($block)) continue;
                if (strpos($block, 'application/octet-stream') !== FALSE) {
                    preg_match("/name=\"([^\"]*)\"; filename=\"([^\"]*)\".*Content-Type: (.*)\n\n(.*)\n$/s", $block, $matches);
                } else {
                    preg_match('/name=\"([^\"]*)\"\r\n\r\n(.*)\r\n/s', $block, $matches);
                }
                $this->putDataCache[$matches[1]] = $matches[2];
            }
        } else {
            parse_str(file_get_contents('php://input'), $this->putDataCache);
        }
    }

    private function parseDeleteData(): void
    {
        if ($this->method() !== 'DELETE') {
            return;
        }

        $contentType = $this->server('CONTENT_TYPE') ?? '';
        if (str_contains($contentType, 'application/json')) {
            $this->deleteDataCache = json_decode(file_get_contents('php://input'), true);
        } else {
            parse_str(file_get_contents('php://input'), $this->deleteDataCache);
        }
    }

    // Dosya yükleme işlemi
    public function upload_files(string $key = ''): string|array
    {
        $this->upload_errors       = [];
        $this->upload_error_code   = 0;

        $uploaded = empty($key) ? [] : '';

        $filenames = array_column($this->files(), 'name');
        $found = false;
        foreach ($filenames as $i => $value) {
            if (!empty($value)) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            return $uploaded;
        }

        $get_one = !empty($key);
        $uploaded = [];

        foreach ($this->files() as $ikey => $file_arr) {
            if (!$this->validateFile($file_arr)) {
                continue;
            }

            $destination = $this->generateFileName($file_arr);

            if (!is_dir($this->upload_folder)) {
                mkdir($this->upload_folder, 0777, true);
            }

            move_uploaded_file($file_arr['tmp_name'], $destination);
            $uploaded[] = $destination;

            if ($get_one) {
                break;
            }
        }

        return $get_one ? ($uploaded[0] ?? '') : $uploaded;
    }

    private function validateFile(array $file): bool
    {
        if ($file['error'] > 0) {
            $this->upload_errors[] = "Error with file: {$file['name']}";
            return false;
        }
        if (!in_array($file['type'], $this->upload_file_types)) {
            $this->upload_errors[] = "Invalid file type: {$file['name']}";
            return false;
        }
        if ($file['size'] > ($this->upload_max_size * 1024 * 1024)) {
            $this->upload_errors[] = "File too large: {$file['name']}";
            return false;
        }
        return true;
    }

    private function generateFileName(array $file): string
    {
        $folder = trim($this->upload_folder, '/') . '/';
        $destination = $folder . $file['name'];

        $num = 0;
        while (file_exists($destination) && $num < 10) {
            $num++;
            $ext = pathinfo($destination, PATHINFO_EXTENSION);
            $destination = preg_replace("/\.$ext$/", "_" . rand(0, 99) . ".$ext", $destination);
        }

        return $destination;
    }

    // Dosya bilgilerini almak
    public function files(string $key = ''): string|array
    {
        if (empty($key)) {
            return $_FILES;
        }

        return $_FILES[$key] ?? '';
    }

    // Genel istek verisini almak
    public function all(string $key = ''): string|array
    {
        if (empty($key)) {
            return $_REQUEST;
        }

        return $_REQUEST[$key] ?? '';
    }
}
