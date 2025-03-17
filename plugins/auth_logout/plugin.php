<?php
use \App\Core\Helpers\ActionFilterHelper;
use \App\Core\Database\Connection;
use App\Core\Helpers\TagsManager;
use \App\Core\Helpers\UrlHelper;
use App\Core\Helpers\ViewHelper;
use App\Core\Http\Route;
use App\Core\Models\Session;
use Auth\Logout\Controllers\LogoutController;

/**
 * Plugin Dev : Lovesta Inc.
 * Plugin Id  : plugin-sample
 * Plugin Name: Sample Plugin
 * Plugin Desc: Sample Plugin for Lovesta Inc.
 */
ActionFilterHelper::setValue([
	"plugin_routes" => 
	[
		"base_route"	=> "auth",
		"logout_route"  => "logout",
	]
]);

ActionFilterHelper::addAction('controller',function()
{
    $routes = ActionFilterHelper::getValue("plugin_routes");
	if(Route::is($routes["base_route"].'/'.$routes["logout_route"]))
	{
		$ses = new Session;
		if($ses->is_logged_in())
		{
			$ses->logout();
			UrlHelper::redirect("auth/login");
		}
	}
});

ActionFilterHelper::addAction('view',function()
{
	$routes 	= ActionFilterHelper::getValue("plugin_routes");
	if(Route::is($routes["base_route"].'/'.$routes["logout_route"]))		
		ViewHelper::viewPlugin(plugin_path('views/view.php'));
});

ActionFilterHelper::addAction('navheader_logout',function()
{
	$routes	= ActionFilterHelper::getValue("plugin_routes");
	echo '<a href="'.ROOT."/".$routes["base_route"].'/'.$routes["logout_route"].'" class="dropdown-item ai-icon"><svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg><span class="ms-1">'.__lang("sign_out").'</span></a>';
});

ActionFilterHelper::addFilter('main-menu_menu_links',function($links)
{
	$routes = ActionFilterHelper::getValue("plugin_routes");

	$link 		 	  = (object)[];
	$link->id 	 	  = 1;
	$link->title 	  = "Oturumu Kapat";
	$link->slug  	  = $routes["base_route"]."/".$routes["logout_route"];
	$link->icon  	  = 'bi bi-door-open';
	$link->permission = 'logged_in';
	$links[] 	 	 = $link;
	return $links;
},60);