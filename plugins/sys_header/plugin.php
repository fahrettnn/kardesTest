<?php
use \App\Core\Helpers\ActionFilterHelper;
use \App\Core\Database\Connection;
use App\Core\Helpers\TagsManager;
use \App\Core\Helpers\UrlHelper;
use App\Core\Helpers\ViewHelper;
use App\Core\Http\Route;
use App\Core\Models\Session;
use Google\Service\Walletobjects\Uri;

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

ActionFilterHelper::addAction('before_controller',function()
{
    $routes = ActionFilterHelper::getValue("plugin_routes");
	$sesion = new Session;

	if(Route::is($routes["auth_route"]."/".$routes["login_route"]) or 
	Route::is($routes["auth_route"]."/".$routes["reset-password"]) or
	Route::is($routes["auth_route"]."/".$routes["reset-password"]."/verify"))
	{
		if($sesion->is_logged_in())
			UrlHelper::redirect("upload-files");
	}
	else
		if(!$sesion->is_logged_in() && UrlHelper::page() != $routes["404_route"])
			UrlHelper::redirect($routes["auth_route"]."/".$routes["login_route"]);
});

ActionFilterHelper::addAction('before_view',function()
{
    $routes = ActionFilterHelper::getValue("plugin_routes");
	$template = match (UrlHelper::page()) {
		$routes['404_route']  => 'views/auth/header_view.php',
		$routes['auth_route'] => 'views/auth/header_view.php',
		default => 'views/header_view.php',
	};
	
	ViewHelper::viewPlugin(plugin_path($template));
});

ActionFilterHelper::addAction('header_headsTags',function()
{
	$headManager = new TagsManager();

	$headManager->addTag('meta',['charset' => 'utf-8']);
	$headManager->addTag('meta',['http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge']);
	$headManager->addTag('meta',['name' => 'viewport',"content" => "width=device-width, initial-scale=1"]);
	$headManager->addTag('link',['rel' => 'shortcut icon', 'type' => 'image/png',"href" => get_image("public/uploads/images/logo.svg")]);
	$headManager->addTag('link',['rel' => "apple-touch-startup-image",'media' => '(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)', "href" => get_image("public/uploads/pwa/android/mipmap-hdpi/ic_launcher.png")]);
	$headManager->addTag('link',['rel' => "apple-touch-startup-image",'media' => '(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)', "href" => get_image("public/uploads/pwa/android/mipmap-mdpi/ic_launcher.png")]);
	$headManager->addTag('link',['rel' => "apple-touch-startup-image",'media' => '(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)', "href" => get_image("public/uploads/pwa/android/mipmap-xhdpi/ic_launcher.png")]);
	$headManager->addTag('link',['rel' => "apple-touch-startup-image",'media' => '(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)', "href" => get_image("public/uploads/pwa/android/mipmap-xxhdpi/ic_launcher.png")]);
	$headManager->addTag('link',['rel' => "apple-touch-startup-image",'media' => '(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)', "href" => get_image("public/uploads/pwa/android/mipmap-xxxhdpi/ic_launcher.png")]);
	$headManager->addTag('link',['rel' => "apple-touch-startup-image",'media' => '(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)', "href" => get_image("public/uploads/pwa/android/mipmap-xxxhdpi/ic_launcher.png")]);
	$headManager->addTag('link',['rel' => "apple-touch-startup-image",'media' => '(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)', "href" => get_image("public/uploads/pwa/android/mipmap-xxxhdpi/ic_launcher.png")]);
	$headManager->addTag('link',['rel' => 'apple-touch-icon', "href" => get_image("public/uploads/pwa/appicon/192x192.png")]);
	$headManager->addTag('link',['rel' => 'apple-touch-icon-precomposed', 'sizes' => '128x128', "href" => get_image("public/uploads/pwa/appicon/128x128.png")]);
	$headManager->addTag('link',['rel' => 'icon', 'sizes' => '192x192',  "href" => get_image("public/uploads/pwa/appicon/192x192.png")]);

	$headManager->addStyle(ROOT."/public/assets/libs/swiper/css/swiper-bundle.min.css",true);
	$headManager->addStyle(ROOT."/public/assets/css/style.css",true);

	$headManager->addTag('link',['rel' => 'manifest', "href" => ROOT."/manifest.json"]);
	$headManager->addTag('meta',['name' => 'apple-mobile-web-app-capable',"content" => "yes"]);
	$headManager->addTag('meta',['name' => 'apple-mobile-web-app-title',"content" => "kardesdocuments.com"]);
	$headManager->addTag('meta',['name' => 'apple-mobile-web-app-status-bar-style',"content" => "black"]);
	$headManager->addScript("if ('serviceWorker' in navigator) { window.addEventListener('load', function () { navigator.serviceWorker.register('" . ROOT . '/plugins/header-footer/assets/js/sw.js' . "'); }); }");
	$headManager->render();
},0);