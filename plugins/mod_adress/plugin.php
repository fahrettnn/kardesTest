<?php
use \App\Core\Helpers\ActionFilterHelper;
use \App\Core\Database\Connection;
use App\Core\Helpers\TagsManager;
use \App\Core\Helpers\UrlHelper;
use App\Core\Helpers\ViewHelper;
use App\Core\Http\Route;
use Google\Service\Compute\Router;
use Mod\Adress\Controllers\CityController;
use Mod\Adress\Controllers\DiscritsController;
use Mod\Customers\Controllers\CustomersController;

/**
 * Plugin Dev : Lovesta Inc.
 * Plugin Id  : plugin-sample
 * Plugin Name: Sample Plugin
 * Plugin Desc: Sample Plugin for Lovesta Inc.
 */
ActionFilterHelper::setValue([
	"plugin_routes" => 
	[
		"base_route"	=> "gen-set",
		"city_route"  => "city-settings",
		"discrits_route" => "discrits-settings"
	],
	"tables" => [
		"city_table" => "tbl_city",
		"discrit_table"	=> "tbl_discrits" 
	],
]);

/** set user permissions for this plugin **/
ActionFilterHelper::addFilter('permissions',function($permissions)
{
    $permissions[] = 'il_görüntüleme';
	$permissions[] = 'il_ekleme';
    $permissions[] = 'il_düzenleme';
	$permissions[] = 'il_silme';
	$permissions[] = 'ilçe_görüntüleme';
	$permissions[] = 'ilçe_ekleme';
    $permissions[] = 'ilçe_düzenleme';
	$permissions[] = 'ilçe_silme';
    return $permissions;
});

ActionFilterHelper::addAction('api',function()
{
	Route::get('api/city', [CityController::class, 'getCityList']);
	Route::post('api/city', [CityController::class, 'addCity']);
	Route::put('api/city', [CityController::class, 'editCity']);
	Route::delete('api/city', [CityController::class, 'deleteCity']);

	Route::get('api/discrits', [DiscritsController::class, 'getList']);
	Route::post('api/discrits', [DiscritsController::class, 'add']);
	Route::put('api/discrits', [DiscritsController::class, 'editDiscrit']);
	Route::delete('api/discrits', [DiscritsController::class, 'deleteDiscrit']);
});

ActionFilterHelper::addAction('view',function()
{
	$routes = ActionFilterHelper::getValue("plugin_routes");

	if(Route::is($routes["base_route"]."/".$routes["city_route"]))
		ViewHelper::viewPlugin(plugin_path('views/city_view.php'));
	if(Route::is($routes["base_route"]."/".$routes["discrits_route"]))
		ViewHelper::viewPlugin(plugin_path('views/discrits_view.php'));
});

ActionFilterHelper::addAction('header_headsTags',function()
{
	$routes 	 = ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();
	if(Route::is($routes["base_route"]."/".$routes["city_route"]))
	{
		$headManager->addTag(tag:'title', content:__lang("general_settings") . ' - İl Ayarları | '. APP_NAME);
		$headManager->addStyle(ROOT.'/public/assets/libs/jquery/datatable/css/jquery.dataTables.min.css',true);
	}
	else if(Route::is($routes["base_route"]."/".$routes["discrits_route"]))
	{
		$headManager->addTag(tag:'title', content:__lang("general_settings") . ' - İlçe Ayarları | '. APP_NAME);
		$headManager->addStyle(ROOT.'/public/assets/libs/jquery/datatable/css/jquery.dataTables.min.css',true);
	}

	$headManager->render();
});

ActionFilterHelper::addAction('footer_scriptsTags',function()
{
	$routes 	 = ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();
	
	if(Route::is($routes["base_route"]."/".$routes["city_route"]))
	{
		$headManager->addScript(ROOT. '/public/assets/libs/jquery/datatable/js/jquery.dataTables.min.js',true);
		$headManager->addScript(ROOT. '/public/assets/libs/jquery/datatable/js/datatables.init.js',true);
		$headManager->addScript(plugin_http_path('assets/js/city.js'),true);
	}
	else if(Route::is($routes["base_route"]."/".$routes["discrits_route"]))
	{
		$headManager->addScript(ROOT. '/public/assets/libs/jquery/datatable/js/jquery.dataTables.min.js',true);
		$headManager->addScript(ROOT. '/public/assets/libs/jquery/datatable/js/datatables.init.js',true);
		$headManager->addScript(plugin_http_path('assets/js/discrits.js'),true);
	}

	$headManager->render();
});

ActionFilterHelper::addFilter('general-setting_sub_menu_links',function($links)
{
	$routes = ActionFilterHelper::getValue("plugin_routes");
	$link 		 	  = (object)[];
	$link->id 	 	  = 1;
	$link->title 	  = "İl Ayarları";
	$link->slug  	  = $routes["city_route"];
	$link->icon  	  = 'bi bi-users';
	$link->permission = 'logged_in';
	$links[] 	 	 = $link;
	return $links;
},2);

ActionFilterHelper::addFilter('general-setting_sub_menu_links',function($links)
{
	$routes = ActionFilterHelper::getValue("plugin_routes");
	$link 		 	  = (object)[];
	$link->id 	 	  = 1;
	$link->title 	  = "İlçe Ayarları";
	$link->slug  	  = $routes["discrits_route"];
	$link->icon  	  = 'bi bi-users';
	$link->permission = 'logged_in';
	$links[] 	 	 = $link;
	return $links;
},3);