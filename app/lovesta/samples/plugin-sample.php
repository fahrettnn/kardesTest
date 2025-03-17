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
		"plugin_route"  => "plugin",
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
		case $routes["auth_route"]:
			$headManager->addTag(tag:'title', content:'< Enter Your Title >')
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

/** for manipulating data after a query operation **/
ActionFilterHelper::addFilter('after_query',function($data)
{
    if(empty($data['result']))
        return $data;

    foreach ($data['result'] as $key => $row) 
    {
    
    }

    return $data;
}); 