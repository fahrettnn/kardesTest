<?php
use Nette\Loaders\RobotLoader;
use Dotenv\Dotenv;
use App\Core\Config;

/** Define */
define('DEBUG', true);
define('APP_NAME', 'Kardeş Sondajcılık');
define('APP_DESCRIPTION', 'The best website framework');
define('DS', DIRECTORY_SEPARATOR);
define('ROOTPATH', realpath('.').DS);

/** GOOGLE DRİVE PATH DEF */
define('TOKEN_PATH', realpath('.').'/token.json');

if((empty($_SERVER['SERVER_NAME']) && strpos(PHP_SAPI, 'cgi') !== 0) || (!empty($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost'))
{
    define('CREDENTIALS_PATH', realpath('.').'/credentials-dev.json');
    define('REDIRECT_URL', 'http://localhost/kardes-app.com.tr/upload-files');
    //define('ARCHIVE_REDIRECT_URL', 'http://localhost/kardes-app.com.tr/archive');
}
else
{
    define('CREDENTIALS_PATH', realpath('.').'/credentials.json');
    define('REDIRECT_URL', 'https://kardes-app.com.tr/upload-files');
    //define('ARCHIVE_REDIRECT_URL', 'https://kardes-app.com.tr/archive');
}


/** Autoload */
$loader = new RobotLoader;
$loader->addDirectory(ROOTPATH . 'app/core');
$loader->addDirectory(ROOTPATH . 'plugins');
$loader->setTempDirectory(ROOTPATH . 'temp');
// geliştirme ortamından sonra burası aktif $loader->reportParseErrors(false);
$loader->register();

/** @var Config [System Config Control] */
$config = new Config("8.0.0",DEBUG);
$config->configRun();
define('ROOT', $config->root());

$dotenv = Dotenv::createImmutable(realpath("."));
$dotenv->load();

//** System Backend Language */
$lang_file = isset($_COOKIE['language']) ? $_COOKIE['language'] : $_ENV["DEFAULT_LANGUAGE"];

$dev_lang_path = ROOTPATH."dev-tools/language/". $lang_file . '/langDev.php';
if (file_exists($dev_lang_path))
    require_once $dev_lang_path;

$system_lang_path = ROOTPATH."language/". $lang_file . '/lang.php';
if (file_exists($system_lang_path))
    require_once $system_lang_path;