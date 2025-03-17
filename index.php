<?php
use \App\Core\Helpers\UrlHelper;
use \App\Core\Helpers\PluginHelper;
use \App\Core\Helpers\ActionFilterHelper;
use \App\Core\App;
use App\Core\Http\Route;

require_once 'vendor/autoload.php';
require_once 'app/core/functions.php';
require_once 'app/core/init.php';

$ACTIONS 			= [];
$FILTERS 			= [];
$APP['URL'] 		= UrlHelper::splitUrl($_GET['url'] ?? UrlHelper::redirect('upload-files'));
$APP['permissions'] = [];
$USER_DATA 			= [];

$PLUGINS 			= PluginHelper::getPluginFolders();
if (!PluginHelper::loadPlugins($PLUGINS)) 
{
	if (defined('DEBUG') && DEBUG)
	{
		$nopluginfile = plugin_path('dev-tools/no-plugin/no-plugin.php');
	    if (file_exists($nopluginfile))
	        require_once $nopluginfile;
	    	die;
	}else { UrlHelper::redirect("404"); }
}

$APP['permissions'] = ActionFilterHelper::doFilter('permissions',$APP['permissions']);
$app = new App();
$app->run();