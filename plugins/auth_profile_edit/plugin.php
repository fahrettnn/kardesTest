<?php
use \App\Core\Helpers\ActionFilterHelper;
use \App\Core\Database\Connection;
use App\Core\Helpers\TagsManager;
use \App\Core\Helpers\UrlHelper;
use App\Core\Helpers\ViewHelper;
use App\Core\Http\Route;
use App\Core\Models\Session;
use Auth\ProfileEdit\Controllers\ProfileEditController;

/**
 * Plugin Dev : Lovesta Inc.
 * Plugin Id  : plugin-sample
 * Plugin Name: Sample Plugin
 * Plugin Desc: Sample Plugin for Lovesta Inc.
 */
ActionFilterHelper::setValue([
	"plugin_routes" => 
	[
		"plugin_route"  => "profile-edit",
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
	Route::post('api/edit-profile', [ProfileEditController::class, 'profileEdit']);
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
	$ses 	= new Session;
	if(Route::is($routes["plugin_route"]))
		ViewHelper::viewPlugin(plugin_path('views/view.php'),['getUser' => $ses->getUser()]);
	
});

ActionFilterHelper::addAction('navheader_profile_edit',function()
{
	$routes	= ActionFilterHelper::getValue("plugin_routes");
	echo '<a href="'.ROOT.'/'.$routes["plugin_route"].'" class="dropdown-item ai-icon"><svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg><span class="ms-2">Profil Düzenle</span></a>';
});

ActionFilterHelper::addAction('header_headsTags',function()
{
	$routes 	 = ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();
	if(Route::is($routes["plugin_route"]))
	{
		$headManager->addTag(tag:'title', content:'Profil Düzenle | '. APP_NAME);
	}
	$headManager->render();
});

ActionFilterHelper::addAction('footer_scriptsTags',function()
{
	$routes 	 = ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();
	if(Route::is($routes["plugin_route"]))
		$headManager->addScript(plugin_http_path('assets/js/edit-profile.js'),true);
	$headManager->render();
});