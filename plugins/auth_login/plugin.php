<?php
use \App\Core\Helpers\ActionFilterHelper;
use App\Core\Helpers\TagsManager;
use App\Core\Helpers\ViewHelper;
use App\Core\Http\Route;
use Auth\Login\Controllers\LoginController;

ActionFilterHelper::setValue([
	"plugin_routes" => 
	[
		"auth_route"	=> "auth",
		"plugin_route"  => "login",
	],
	"tables" => [
		"plugin_tables" => "tbl_users", 
	],
]);

ActionFilterHelper::addAction('api',function()
{
	Route::post('api/auth/login', [LoginController::class, 'login']);
});

ActionFilterHelper::addAction('view',function()
{
	$routes 	= ActionFilterHelper::getValue("plugin_routes");
	if(Route::is($routes["auth_route"].'/'.$routes["plugin_route"]))		
		ViewHelper::viewPlugin(plugin_path('views/view.php'));
});

ActionFilterHelper::addAction('header_headsTags',function()
{
	$routes 	 = ActionFilterHelper::getValue("plugin_routes");
	if(Route::is($routes["auth_route"].'/'.$routes["plugin_route"]))
	{
		$headManager = new TagsManager();
		$headManager->addTag(tag:'title', content:"Giriş Yap | ". APP_NAME);
		$headManager->render();
	}
});

ActionFilterHelper::addAction('footer_scriptsTags',function()
{
	$routes 	= ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();
	if(Route::is($routes["auth_route"].'/'.$routes["plugin_route"]))
	{
		$headManager->addScript(plugin_http_path('assets/js/login.js'),true);
	}
	$headManager->render();
});