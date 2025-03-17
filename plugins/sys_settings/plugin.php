<?php
use \App\Core\Helpers\ActionFilterHelper;
use \App\Core\Database\Connection;
use App\Core\Helpers\TagsManager;
use \App\Core\Helpers\UrlHelper;
use App\Core\Helpers\ViewHelper;
use App\Core\Http\Route;
use Settings\Controllers\SettingController;

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
		"plugin_route"  => "general-settings",
	],
]);

/** set user permissions for this plugin **/
ActionFilterHelper::addFilter('permissions',function($permissions)
{
	$permissions[] = 'genel_ayarlar_görüntüleme';
	$permissions[] = 'genel_ayarlar_düzenleme';
    return $permissions;
});

ActionFilterHelper::addAction('api',function()
{
    $vars = ActionFilterHelper::getValue();
	$routes = $vars["plugin_routes"];
	
	switch (UrlHelper::URL(1)) {
		case $routes["plugin_route"]:
            $controller = new SettingController;
            $controller->requestSettings();
			break;
	}
});

ActionFilterHelper::addAction('view',function()
{
	$vars = ActionFilterHelper::getValue();
	$routes = $vars["plugin_routes"];
	if(Route::is($routes["base_route"]."/".$routes["plugin_route"]))
		ViewHelper::viewPlugin(plugin_path('views/view.php'));
});

ActionFilterHelper::addAction('header_headsTags',function()
{
	$routes 	 = ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();
	if(Route::is($routes["base_route"]."/".$routes["plugin_route"]))
		$headManager->addTag(tag:'title', content:__lang('general_settings') . " - " . APP_NAME);
	
	$headManager->render();
});

ActionFilterHelper::addAction('footer_scriptsTags',function()
{
	$routes 	= ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();
	if(Route::is($routes["base_route"]."/".$routes["plugin_route"]))
		$headManager->addScript(plugin_http_path('assets/js/plugin.js'),true);
	$headManager->render();
});

ActionFilterHelper::addFilter('main-menu_menu_links',function($links)
{
	$route 			  = ActionFilterHelper::getValue("plugin_routes");
	$link 		 	  = (object)[];
	$link->id 	 	  = 1;
	$link->title 	  = __lang("general_settings");
	$link->slug  	  = $route["base_route"];
	$link->icon  	  = 'bi bi-gear-wide-connected';
	$link->permission = 'logged_in';
	$childrens 		  = [];
	$link->childrens  = ActionFilterHelper::doFilter(plugin_id().'_sub_menu_links',$childrens);
	$links[] 	 	  = $link;
	return $links;
},45);

ActionFilterHelper::addFilter(plugin_id().'_sub_menu_links',function($links)
{
	$route = ActionFilterHelper::getValue("plugin_routes");
	$link 		 	  = (object)[];
	$link->id 	 	  = 1;
	$link->title 	  = __lang("general_settings");
	$link->slug  	  = $route["plugin_route"];
	$link->icon  	  = 'bi bi-users';
	$link->permission = 'logged_in';
	$links[] 	 	 = $link;
	return $links;
},1);