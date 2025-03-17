<?php
use \App\Core\Helpers\ActionFilterHelper;
use \App\Core\Database\Connection;
use App\Core\Helpers\TagsManager;
use \App\Core\Helpers\UrlHelper;
use App\Core\Helpers\ViewHelper;

/**
 * Plugin Dev : Lovesta Inc.
 * Plugin Id  : plugin-sample
 * Plugin Name: Sample Plugin
 * Plugin Desc: Sample Plugin for Lovesta Inc.
 */
ActionFilterHelper::setValue([
	"plugin_routes" => 
	[
		"plugin_route"  => "upload-files",
	],
	"tables" => [
		"plugin_tables" => "plugins", 
	],
]);

/** set user permissions for this plugin **/
ActionFilterHelper::addFilter('permissions',function($permissions)
{
    $permissions[] = 'plugin_view_permission';
    $permissions[] = 'plugin_add_permission';
	$permissions[] = 'plugin_edit_permission';
    $permissions[] = 'plugin_delete_permission';
    return $permissions;
});

ActionFilterHelper::addAction('api',function()
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

	switch (UrlHelper::page()) {
		case $routes["plugin_route"]:
			ViewHelper::viewPlugin(plugin_path('views/view.php'));
			break;
	}
});

ActionFilterHelper::addAction('header_headsTags',function()
{
	$routes 	 = ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();

	switch (UrlHelper::page())
	{
		case $routes["plugin_route"]:
			$headManager->addTag(tag:'title', content:'Dosya Yükleme | '. APP_NAME)
                        ->addStyle(ROOT. '< Enter path Url >',true)
						->addStyle(plugin_http_path('< Enter path Url >'),true);
			break;
	}
	$headManager->render();
});

ActionFilterHelper::addAction('footer_scriptsTags',function()
{
	$routes 	= ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();
	switch (UrlHelper::page())
	{
		case $routes["plugin_route"]:
			$headManager->addScript(ROOT.'< Enter path Url >',true)
						->addScript(plugin_http_path('< Enter path Url >'),true);
			break;
	}
	
	$headManager->render();
});

ActionFilterHelper::addFilter('main-menu_menu_links',function($links)
{
	$routes 	= ActionFilterHelper::getValue("plugin_routes");
	$link 		 	  	= (object)[];
	$link->id 	 	  	= 1;
	$link->title 	  	= "Dosya Yükleme";
	$link->slug  	  	= $routes["plugin_route"];
	$link->icon  	  	= 'bi bi-folder-symlink';
	$link->permission 	= 'logged_in';
	$links[] 	 	 	= $link;
	return $links;
	
},2);
