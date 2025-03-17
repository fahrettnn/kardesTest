<?php
namespace App\Core\Helpers;

// ROOT sabiti tanımlı değilse doğrudan erişimi engelle.
defined('ROOT') or die("Direct script access denied");

/**
 * ActionFilterHelper sınıfı, eklentiler ve sistem genelinde aksiyon ve filtre mekanizmaları sağlar.
 * Kullanıcı verilerini yönetmek ve belirli eylemleri/dönüşümleri kolaylaştırmak için yardımcı fonksiyonlar içerir.
 */
class ActionFilterHelper 
{
    // Aksiyonlar ve filtreler için diziler.
    private static $actions = [];
    private static $filters = [];
    private static $userData = [];
    private static $configCache = [];

    /**
     * Yeni bir aksiyon ekler.
     *
     * @param string $hook Aksiyon adı.
     * @param callable $func Çalıştırılacak fonksiyon.
     * @param int $priority Öncelik sırası (varsayılan 10).
     * @return bool Başarı durumu.
     *
     * Örnek Kullanım:
     * ActionFilterHelper::addAction('user_registered', function($data) {
     *     echo 'Yeni kullanıcı kaydedildi: ' . $data['username'];
     * });
     */
    public static function addAction(string $hook, callable $func, int $priority = 10): bool
    {
        if (!isset(self::$actions[$hook])) {
            self::$actions[$hook] = [];
        }

        while (isset(self::$actions[$hook][$priority])) {
            $priority++;
        }

        self::$actions[$hook][$priority] = $func;
        ksort(self::$actions[$hook]); // Öncelik sırasına göre düzenle.
        return true;
    }

    /**
     * Belirtilen aksiyonu çalıştırır.
     *
     * @param string $hook Aksiyon adı.
     * @param array $data Aksiyon için veriler.
     *
     * Örnek Kullanım:
     * ActionFilterHelper::doAction('user_registered', ['username' => 'Ali']);
     */
    public static function doAction(string $hook, array $data = []): void
    {
        if (!empty(self::$actions[$hook])) {
            foreach (self::$actions[$hook] as $func) {
                if (is_callable($func)) {
                    $func($data);
                }
            }
        }
    }

    /**
     * Yeni bir filtre ekler.
     *
     * @param string $hook Filtre adı.
     * @param callable $func Uygulanacak fonksiyon.
     * @param int $priority Öncelik sırası (varsayılan 10).
     * @return bool Başarı durumu.
     *
     * Örnek Kullanım:
     * ActionFilterHelper::addFilter('sanitize_input', function($data) {
     *     return trim(strip_tags($data));
     * });
     */
    public static function addFilter(string $hook, callable $func, int $priority = 10): bool
    {
        if (!isset(self::$filters[$hook])) {
            self::$filters[$hook] = [];
        }

        while (isset(self::$filters[$hook][$priority])) {
            $priority++;
        }

        self::$filters[$hook][$priority] = $func;
        ksort(self::$filters[$hook]); // Öncelik sırasına göre düzenle.
        return true;
    }

    /**
     * Belirtilen filtreyi çalıştırır ve sonucu döndürür.
     *
     * @param string $hook Filtre adı.
     * @param mixed $data Filtrelenecek veri.
     * @return mixed Filtrelenmiş veri.
     *
     * Örnek Kullanım:
     * $sanitized = ActionFilterHelper::doFilter('sanitize_input', '<script>alert("xss")</script>');
     */
    public static function doFilter(string $hook, mixed $data = ''): mixed
    {
        if (!empty(self::$filters[$hook])) {
            foreach (self::$filters[$hook] as $func) {
                if (is_callable($func)) {
                    $data = $func($data);
                }
            }
        }

        return $data;
    }

    /**
     * Kullanıcıya özel veri kaydeder.
     *
     * @param mixed $key Anahtar (veya anahtar-değer çifti).
     * @param mixed $value Değer (anahtar belirtilirse).
     * @return bool Başarı durumu.
     *
     * Örnek Kullanım:
     * ActionFilterHelper::setValue('user_settings', ['theme' => 'dark']);
     */
    public static function setValue(mixed $key, mixed $value = ''): bool 
    {
        $pluginId = self::getPluginId();
        if (!$pluginId) {
            return false;
        }

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                self::$userData[$pluginId][$k] = $v;
            }
        } else {
            self::$userData[$pluginId][$key] = $value;
        }

        return true;
    }

    /**
     * Kullanıcıya özel veri alır.
     *
     * @param string $key Anahtar (boş bırakılırsa tüm veriler).
     * @return mixed Alınan değer.
     *
     * Örnek Kullanım:
     * $settings = ActionFilterHelper::getValue('user_settings');
     */
    public static function getValue(string $key = ''): mixed 
    {
        $pluginId = self::getPluginId();
        if (!$pluginId) {
            return null;
        }

        if (empty($key)) {
            return self::$userData[$pluginId] ?? null;
        }

        return self::$userData[$pluginId][$key] ?? null;
    }

    /**
     * Mevcut eklenti kimliğini döndürür.
     *
     * @return string|null Eklenti ID veya null.
     */
    private static function getPluginId(): ?string
    {
        $calledFrom = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['file'] ?? null;
        if (!$calledFrom) {
            return null;
        }

        $configPath = self::getPluginConfigPath($calledFrom);
        if (!$configPath || !file_exists($configPath)) {
            return null;
        }

        if (!isset(self::$configCache[$configPath])) {
            $configData = json_decode(file_get_contents($configPath), true);
            if (json_last_error() !== JSON_ERROR_NONE || empty($configData['plugin_settings']['id'])) {
                return null;
            }
            self::$configCache[$configPath] = $configData['plugin_settings']['id'];
        }

        return self::$configCache[$configPath];
    }

    /**
     * Eklenti yapılandırma dosyasının yolunu döndürür.
     *
     * @param string $filePath Eklenti dosya yolu.
     * @return string Yapılandırma dosyası yolu.
     */
    private static function getPluginConfigPath(string $filePath): string
    {
        // get_plugin_dir fonksiyonu çağrılır.
        return get_plugin_dir($filePath) . 'config.json';
    }
}
