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
		"auth_route"	=> "auth",
		"login_route"  	=> "login",
		"reset-password"=> "reset-password",
		"404_route"		=> "404"
	]
]);

ActionFilterHelper::addAction('after_view',function()
{
    $routes = ActionFilterHelper::getValue("plugin_routes");
	$template = match (UrlHelper::page()) {
		$routes['404_route']  => 'views/auth/footer_view.php',
		$routes['auth_route'] => 'views/auth/footer_view.php',
		default => 'views/footer_view.php',
	};
	ViewHelper::viewPlugin(plugin_path($template));
});

ActionFilterHelper::addAction('footer_scriptsTags',function()
{
	$routes 	= ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();

	$headManager->addScript(ROOT."/public/assets/libs/global/global.min.js",true);
	$headManager->addScript(ROOT."/public/assets/libs/sweetalert2/sweetalert2.js",true);
	$headManager->addScript(ROOT."/public/assets/js/swall.js",true);
	//$headManager->addScript(plugin_http_path('/assets/js/sw.js'),true); indirme 

	switch (UrlHelper::page())
	{
		case $routes["404_route"]:
		case $routes["auth_route"]:
			$headManager->addScript(ROOT."/public/assets/libs/swiper/js/swiper-bundle.min.js",true);
			break;
		default:
			$headManager->addScript(ROOT."/public/assets/js/custom.js",true);
			$headManager->addScript(ROOT."/public/assets/js/dlabnav-init.js",true);
			$headManager->addScript(ROOT."/public/assets/js/main-root.js",true);
			$headManager->addScript(ROOT."/public/assets/js/style-switcher.js",true);
			break;
	}
	$headManager->render();
},0);