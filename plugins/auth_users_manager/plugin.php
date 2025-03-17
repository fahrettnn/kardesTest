<?php
use \App\Core\Helpers\ActionFilterHelper;
use \App\Core\Database\Connection;
use App\Core\Helpers\TagsManager;
use \App\Core\Helpers\UrlHelper;
use App\Core\Helpers\ViewHelper;
use App\Core\Http\Route;
use Auth\UsersManager\Controllers\UserController;

/**
 * Plugin Dev : Lovesta Inc.
 * Plugin Id  : plugin-sample
 * Plugin Name: Sample Plugin
 * Plugin Desc: Sample Plugin for Lovesta Inc.
 */
ActionFilterHelper::setValue([
	"plugin_routes" => 
	[
		"base_route"	=> "personnel-settings",
		"plugin_route"  => "personnel-transcation",
	],
	"tables" => [
		"managers_table" 	=> "tbl_users",
		"roles_table" 		=> "tbl_user_roles",
		"roles_map_table" 	=> "tbl_user_roles_map",
	],
]);

/** set user permissions for this plugin **/
ActionFilterHelper::addFilter('permissions',function($permissions)
{
	$permissions[] = 'personelleri_görüntüle';
	$permissions[] = 'personel_ekle';
	$permissions[] = 'personel_düzenle';
	$permissions[] = 'personel_sil';
	$permissions[] = 'personel_detay_görüntüle';
	$permissions[] = 'personel_rolü_güncelle';
    return $permissions;
});

ActionFilterHelper::addAction('api',function()
{
    $routes = ActionFilterHelper::getValue("plugin_routes");
	Route::get('api/personnel-transcation', [UserController::class, 'getList']);
	Route::post('api/personnel-transcation', [UserController::class, 'createUser']);
	Route::put('api/personnel-transcation', [UserController::class, 'updateUser']);
	Route::delete('api/personnel-transcation', [UserController::class, 'deleteUser']);
});

ActionFilterHelper::addAction('view',function()
{
	$vars 	= ActionFilterHelper::getValue();
	$routes = $vars["plugin_routes"];

	if(Route::is($routes["base_route"].'/'.$routes["plugin_route"]))
		ViewHelper::viewPlugin(plugin_path('views/view.php'));
	
});

ActionFilterHelper::addAction('header_headsTags',function()
{
	$routes 	 = ActionFilterHelper::getValue("plugin_routes");
	if(Route::is($routes["base_route"].'/'.$routes["plugin_route"]))
	{
		$headManager = new TagsManager();
		$headManager->addTag(tag:'title', content:'Personel İşlemleri | '.APP_NAME);
		$headManager->addStyle(ROOT.'/public/assets/libs/jquery/datatable/css/jquery.dataTables.min.css',true);
		$headManager->render();
	}
});

ActionFilterHelper::addAction('footer_scriptsTags',function()
{
	$routes 	= ActionFilterHelper::getValue("plugin_routes");
	if(Route::is($routes["base_route"].'/'.$routes["plugin_route"]))
	{
		$headManager = new TagsManager();
		$headManager->addScript(ROOT.'/public/assets/libs/jquery/datatable/js/jquery.dataTables.min.js',true);
		$headManager->addScript(ROOT.'/public/assets/libs/jquery/datatable/js/datatables.init.js',true);
		$headManager->addScript(plugin_http_path('assets/js/user-manager.js'),true);
		$headManager->render();
	}
});

ActionFilterHelper::addFilter('personel-authentication_sub_menu_links',function($links)
{
	$route = ActionFilterHelper::getValue("plugin_routes");
	$link 		 	  = (object)[];
	$link->id 	 	  = 1;
	$link->title 	  = __lang("personnel_transcation");
	$link->slug  	  = $route["plugin_route"];
	$link->icon  	  = 'bi bi-users';
	$link->permission = 'logged_in';
	$links[] 	 	 = $link;
	return $links;
},0);