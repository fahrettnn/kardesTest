<?php 
namespace App\Core\Helpers;

use \App\Core\Helpers\UrlHelper;
use Exception;

defined('ROOT') or die("Direct script access denied");

class PluginHelper
{
    const PLUGIN_DIR = 'plugins/';

    /**
     * Get a list of all plugin folders.
     *
     * @return array
     */
    public static function getPluginFolders(): array
    {
        $folders = scandir(self::PLUGIN_DIR);
        return array_filter($folders, fn($folder) => $folder !== '.' && $folder !== '..' && is_dir(self::PLUGIN_DIR . $folder));
    }

    /**
     * Load and initialize plugins.
     *
     * @param array $pluginFolders
     * @return bool
     * @throws Exception
     */
    public static function loadPlugins(array $pluginFolders): bool
    {
        global $APP;

        $loaded = false;
        $APP['plugins'] = [];

        foreach ($pluginFolders as $folder) {
            $configPath = self::PLUGIN_DIR . $folder . '/config.json';

            if (!file_exists($configPath)) {
                throw new Exception("Config file not found: $configPath");
            }

            $pluginData = json_decode(file_get_contents($configPath));
            if (!self::isValidPluginConfig($pluginData)) {
                throw new Exception("Invalid plugin configuration in: $configPath");
            }

            if (!empty($pluginData->plugin_settings->active) && self::validRoute($pluginData)) {
                $pluginData->plugin_settings->index = $pluginData->plugin_settings->index ?? 1;
                $pluginData->plugin_settings->version = $pluginData->plugin_settings->version ?? "1.0.0";
                $pluginData->plugin_settings->dependencies = $pluginData->plugin_settings->dependencies ?? (object)[];

                $pluginData->index_file = self::PLUGIN_DIR . $folder . '/plugin.php';
                $pluginData->path = self::PLUGIN_DIR . $folder . '/';
                $pluginData->http_path = ROOT . '/' . $pluginData->path;

                $APP['plugins'][] = $pluginData;
            }
        }

        $APP['plugins'] = self::sortPlugins($APP['plugins']);

        foreach ($APP['plugins'] as $plugin) {
            self::checkDependencies($plugin);

            if (file_exists($plugin->index_file)) {
                require_once $plugin->index_file;
                $loaded = true;
            }
        }

        return $loaded;
    }

    /**
     * Check if a plugin configuration is valid.
     *
     * @param object|null $pluginData
     * @return bool
     */
    private static function isValidPluginConfig(?object $pluginData): bool
    {
        return isset($pluginData->plugin_settings, $pluginData->plugin_settings->id);
    }

    /**
     * Validate and check plugin dependencies.
     *
     * @param object $plugin
     * @throws Exception
     */
    private static function checkDependencies(object $plugin): void
    {
        if (empty((array)$plugin->plugin_settings->dependencies)) {
            return;
        }

        foreach ((array)$plugin->plugin_settings->dependencies as $pluginId => $version) {
            $dependency = self::pluginExists($pluginId);

            if (!$dependency) {
                throw new Exception("Missing dependency: $pluginId version $version (Required by: {$plugin->plugin_settings->id})");
            }

            $requiredVersion = (int)str_replace('.', '', $version);
            $existingVersion = (int)str_replace('.', '', $dependency->plugin_settings->version);

            if ($existingVersion < $requiredVersion) {
                throw new Exception("Dependency version mismatch for $pluginId: Required $version, Found {$dependency->plugin_settings->version}");
            }
        }
    }

    /**
     * Check if a plugin exists.
     *
     * @param string $pluginId
     * @return object|false
     */
    public static function pluginExists(string $pluginId)
    {
        foreach (self::getPluginFolders() as $folder) {
            $configPath = self::PLUGIN_DIR . $folder . '/config.json';

            if (file_exists($configPath)) {
                $pluginData = json_decode(file_get_contents($configPath));
                if ($pluginData->plugin_settings->id === $pluginId && !empty($pluginData->plugin_settings->active)) {
                    return $pluginData;
                }
            }
        }

        return false;
    }

    /**
     * Sort plugins based on their index.
     *
     * @param array $plugins
     * @return array
     */
    public static function sortPlugins(array $plugins): array
    {
        usort($plugins, fn($a, $b) => $a->plugin_settings->index <=> $b->plugin_settings->index);
        return $plugins;
    }

    /**
     * Check if the current route is valid for a plugin.
     *
     * @param object $pluginData
     * @return bool
     */
    public static function validRoute(object $pluginData): bool
    {
        $currentPage = UrlHelper::page();

        if (!empty($pluginData->plugin_settings->routes->off) && in_array($currentPage, $pluginData->plugin_settings->routes->off)) {
            return false;
        }

        if (!empty($pluginData->plugin_settings->routes->on)) {
            return $pluginData->plugin_settings->routes->on[0] === 'all' || in_array($currentPage, $pluginData->plugin_settings->routes->on);
        }

        return true;
    }

    public static function getApp($key = '')
    {
        global $APP;

        if (!empty($key)):
            return !empty($APP[$key]) ? $APP[$key] : null;
        else:
            return $APP;
        endif;

        return null;
    }

    public static function showPlugins()
    {
        global $APP;
        $plugin_settings = array_column($APP['plugins'], 'plugin_settings');
        $names = array_column($plugin_settings, 'name');
        return $names ?? [];
    }
}
