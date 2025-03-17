<?php
use \App\Core\Helpers\ActionFilterHelper;
use \App\Core\Database\Connection;
use App\Core\Helpers\TagsManager;
use \App\Core\Helpers\UrlHelper;
use App\Core\Helpers\ViewHelper;
use App\Core\Http\Route;
use App\Core\Models\Security;
use Auth\Reset\Password\Controllers\ResetPasswordController;

/**
 * Plugin Dev : Lovesta Inc.
 * Plugin Id  : plugin-sample
 * Plugin Name: Sample Plugin
 * Plugin Desc: Sample Plugin for Lovesta Inc.
 */
ActionFilterHelper::setValue([
	"plugin_routes" => 
	[
		"auth_route"	=> "auth",
		"reset_route"  => "reset-password",
	],
	"tables" => [
		"plugin_tables" => "tbl_users", 
	],
]);

ActionFilterHelper::addAction('api',function()
{
	Route::post('api/auth/reset-password', [ResetPasswordController::class, 'resetPassword']);
	Route::post('api/auth/reset-password/verify', [ResetPasswordController::class, 'resetPasswordVerify']);
});

ActionFilterHelper::addAction('login-plugin_resetLoginBtn',function()
{
	$routes = ActionFilterHelper::getValue("plugin_routes");
    echo '<div class="text-center mb-3">
		<a class=" style-1" href="'.ROOT.'/'.$routes["auth_route"].'/'.$routes['reset_route'].'">Parolanızı mı unuttunuz?</a>
	</div>';
});

ActionFilterHelper::addAction('view',function()
{
	$routes = ActionFilterHelper::getValue("plugin_routes");
	if(Route::is($routes["auth_route"].'/'.$routes["reset_route"]))
		ViewHelper::viewPlugin(plugin_path('views/view.php'));
	else if(Route::is($routes["auth_route"].'/'.$routes["reset_route"].'/verify') && Route::hasQueryParam('email') && Route::hasQueryParam('code'))
		ViewHelper::viewPlugin(plugin_path('views/verify_view.php'));
});

ActionFilterHelper::addAction('header_headsTags',function()
{
	$routes 	 = ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();
	if(Route::is($routes["auth_route"].'/'.$routes["reset_route"]))
		$headManager->addTag(tag:'title', content:'Parolanızı mı unuttunuz? | ' . APP_NAME);
	
	$headManager->render();
});

ActionFilterHelper::addAction('footer_scriptsTags',function()
{
	$routes 	= ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();
	if(Route::is($routes["auth_route"].'/'.$routes["reset_route"]))
		$headManager->addScript(plugin_http_path('assets/js/reset-password.js'),true);
	$headManager->render();
});