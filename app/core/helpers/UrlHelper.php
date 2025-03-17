<?php

namespace App\Core\Helpers;

defined('ROOT') or die("Direct script access denied");

class UrlHelper
{
    /**
     * Split the given URL into segments.
     * Verilen URL'yi parçalarına böler.
     * 
     * @param string $url The URL to split / Parçalanacak URL
     * @return array An array of URL segments / URL parçalarını içeren bir dizi
     */
    public static function splitUrl($url)
    {
        return explode("/", trim($url, '/'));
    }

    /**
     * Retrieve a specific part of the URL or the entire URL array.
     * URL'nin belirli bir parçasını veya tüm URL dizisini döndürür.
     * 
     * @param mixed $key The index or key to retrieve from the URL array / URL dizisinden alınacak indeks veya anahtar
     * @return mixed The requested URL segment or the entire array / İstenen URL parçası veya tüm dizi
     */
    public static function URL($key = '')
    {
        global $APP;

        // Check if $APP['URL'] is set to avoid potential errors
        // Olası hataları önlemek için $APP['URL'] kontrol edilir
        if (!isset($APP['URL']) || !is_array($APP['URL'])) {
            return '';
        }

        if (is_numeric($key) || !empty($key)) {
            return $APP['URL'][$key] ?? '';
        }

        return $APP['URL'];
    }

    /**
     * Get the current page from the URL segments.
     * URL parçalarından mevcut sayfayı döndürür.
     * 
     * @return string The current page or default 'home' / Mevcut sayfa veya varsayılan 'home'
     */
    public static function page()
    {
        return self::URL(0) ?: 'home'; // Default to 'home' if no page is specified / Belirtilmediyse varsayılan 'home'
    }

    /**
     * Redirect to a specified URL.
     * Belirtilen bir URL'ye yönlendirme yapar.
     * 
     * @param string $url The URL to redirect to / Yönlendirilecek URL
     * @param int $httpResponseCode The HTTP response code (default: 302) / HTTP yanıt kodu (varsayılan: 302)
     * @return void
     */
    public static function redirect($url, $httpResponseCode = 302)
    {
        // Use a relative or absolute URL
        // Göreceli veya mutlak URL kullanımı
        $finalUrl = filter_var($url, FILTER_SANITIZE_URL);

        header("Location: " . ROOT . '/' . ltrim($finalUrl, '/'), true, $httpResponseCode);
        exit;
    }

    /**
     * Get the cleaned URI without script paths.
     * Script yolları olmadan temizlenmiş URI döndürür.
     * 
     * @return string The cleaned URI / Temizlenmiş URI
     */
    public static function getCleanedUri()
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $scriptDir = dirname($scriptName);

        $cleanedUri = strpos($requestUri, $scriptDir) === 0
            ? substr($requestUri, strlen($scriptDir))
            : $requestUri;

        return trim(parse_url($cleanedUri, PHP_URL_PATH), '/');
    }

    /**
     * Build a URL with query parameters.
     * Sorgu parametreleriyle bir URL oluşturur.
     * 
     * @param string $path The base path of the URL / URL'nin temel yolu
     * @param array $queryParams An associative array of query parameters / Sorgu parametrelerini içeren dizi
     * @return string The full URL with query parameters / Sorgu parametreleriyle tam URL
     */
    public static function buildUrl($path, array $queryParams = [])
    {
        $queryString = http_build_query($queryParams);
        return ROOT . '/' . trim($path, '/') . ($queryString ? '?' . $queryString : '');
    }

    /*
     * Get the full current URL, including scheme, host, path, and query parameters.
     * Mevcut isteğin tam URL'sini (protokol, host, yol, sorgu parametreleri dahil) döndürür.
     *
     * @return string The full current URL / Tam mevcut URL
     */
    public static function getCurrentUrl()
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        return $scheme . '://' . $host . $requestUri;
    }

    /**
     * Get the base URL (scheme + host) of the current request.
     * Mevcut isteğin temel URL'sini (protokol + host) döndürür.
     *
     * @return string The base URL / Temel URL
     */
    public static function getBaseUrl()
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host;
    }

    /**
     * Redirect the user back to the referring page.
     * Kullanıcıyı geldiği sayfaya geri yönlendirir.
     *
     * @param string $default The default URL to redirect to if HTTP_REFERER is not set / HTTP_REFERER yoksa yönlendirilecek varsayılan URL
     * @param int $httpResponseCode The HTTP response code (default: 302) / HTTP yanıt kodu (varsayılan: 302)
     * @return void
     */
    public static function redirectBack($default = 'home', $httpResponseCode = 302)
    {
        if (!empty($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER'], true, $httpResponseCode);
            exit;
        }
        self::redirect($default, $httpResponseCode);
    }

    /**
     * Retrieve a specific query parameter from the current URL.
     * Mevcut URL'den belirli bir sorgu parametresini döndürür.
     *
     * @param string $key The key of the query parameter / Sorgu parametresinin anahtarı
     * @param mixed $default The default value if the key does not exist / Anahtar yoksa varsayılan değer
     * @return mixed The value of the query parameter or default / Sorgu parametresinin değeri veya varsayılan
     */
    public static function getQueryParam($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Append query parameters to an existing URL.
     * Var olan bir URL'ye sorgu parametreleri ekler.
     *
     * @param string $url The base URL / Temel URL
     * @param array $queryParams Associative array of query parameters to append / Eklenmek istenen sorgu parametreleri
     * @return string The URL with appended query parameters / Sorgu parametreleri eklenmiş URL
     */
    public static function appendQueryParams($url, array $queryParams)
    {
        // Belirli bir URL'de zaten sorgu varsa '&', yoksa '?' kullanılır.
        $separator = parse_url($url, PHP_URL_QUERY) ? '&' : '?';
        return $url . $separator . http_build_query($queryParams);
    }

    /**
     * Force the current request to use HTTPS.
     * Mevcut isteğin HTTPS üzerinden gerçekleşmesini sağlar.
     *
     * @return void
     */
    public static function forceHttps()
    {
        if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
            $httpsUrl = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '');
            header("Location: " . $httpsUrl, true, 301);
            exit;
        }
    }

    public static function isValidUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    public static function slugify($text)
    {
        // Türkçe karakterleri İngilizce'ye çevirme işlemleri eklenebilir
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        return $text ?: 'n-a';
    }

    public static function parseUrlComponents($url)
    {
        return parse_url($url);
    }
}
