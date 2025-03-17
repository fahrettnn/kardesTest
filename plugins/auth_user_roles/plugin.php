<?php
use \App\Core\Helpers\ActionFilterHelper;
use App\Core\Helpers\TagsManager;
use \App\Core\Helpers\UrlHelper;
use App\Core\Helpers\ViewHelper;
use App\Core\Http\Route;
use Auth\UserRoles\Controllers\UserRolePermissionController;
use Auth\UserRoles\Controllers\UserRolesController;

ActionFilterHelper::setValue([
	"plugin_routes" => 
	[
		"base_route"	=> "personnel-settings",
		"plugin_route"  => "user-roles",
	],
	"tables" => [
		"plugin_tables" => "plugins", 
	],
]);

/** set user permissions for this plugin **/
ActionFilterHelper::addFilter('permissions',function($permissions)
{
    $permissions[] = 'rolleri_görüntüle';
	$permissions[] = 'rolleri_ekleme';
	$permissions[] = 'rolleri_düzenleme';
	$permissions[] = 'rolleri_silme';
	$permissions[] = 'izinleri_düzenle';
    return $permissions;
});

ActionFilterHelper::addAction('api',function()
{
    $routes = ActionFilterHelper::getValue("plugin_routes");
	Route::get('api/auth/user-roles', [UserRolesController::class, 'getRole']);
	Route::post('api/auth/user-roles', [UserRolesController::class, 'addRole']);
	Route::put('api/auth/user-roles', [UserRolesController::class, 'updateRole']);
	Route::delete('api/auth/user-roles', [UserRolesController::class, 'deleteRole']);

	Route::post('api/auth/user-role-permissions', [UserRolePermissionController::class, 'requestRolePermission']);
	Route::put('api/auth/user-role-permissions', [UserRolePermissionController::class, 'requestRolePermission']);
});

ActionFilterHelper::addAction('controller',function()
{
    $vars = ActionFilterHelper::getValue();
	$routes = $vars["plugin_routes"];
	
	switch (UrlHelper::page()) {
		case $routes["plugin_route"]:
            /**
             * $controller = new Controller;
             * $controller->method();
             */
			break;
	}
});

ActionFilterHelper::addAction('view',function()
{
	$vars = ActionFilterHelper::getValue();
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
		$headManager->addTag(tag:'title', content:'Personel Rolleri | '.APP_NAME);
		$headManager->addStyle(ROOT.'/public/assets/libs/jquery/datatable/css/jquery.dataTables.min.css',true);
		$headManager->render();
	}
});

ActionFilterHelper::addAction('footer_scriptsTags',function()
{
	$routes 	 = ActionFilterHelper::getValue("plugin_routes");
	
	if(Route::is($routes["base_route"].'/'.$routes["plugin_route"]))
	{
		$headManager = new TagsManager();
		$headManager->addScript(ROOT.'/public/assets/libs/jquery/datatable/js/jquery.dataTables.min.js',true);
		$headManager->addScript(ROOT.'/public/assets/libs/jquery/datatable/js/datatables.init.js',true);
		$headManager->addScript(plugin_http_path('assets/js/user-roles.js'),true);
		$headManager->render();
	}
});

ActionFilterHelper::addFilter('personel-authentication_sub_menu_links',function($links)
{
	$routes = ActionFilterHelper::getValue("plugin_routes");
	$link 		 	  = (object)[];
	$link->id 	 	  = 1;
	$link->title 	  = "Personel Rolleri";
	$link->slug  	  = $routes["plugin_route"];
	$link->icon  	  = 'bi bi-users';
	$link->permission = 'logged_in';
	$links[] 	 	 = $link;
	return $links;
},1);