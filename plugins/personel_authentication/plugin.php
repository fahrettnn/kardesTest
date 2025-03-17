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
		"plugin_route"  => "personnel-settings",
	],
]);

ActionFilterHelper::addFilter('main-menu_menu_links',function($links)
{
	$routes 			= ActionFilterHelper::getValue("plugin_routes");
	$link 		 	  	= (object)[];
	$link->id 	 	  	= 1;
	$link->title 	  	= "Personel Ayarları";
	$link->slug  	 	= $routes["plugin_route"];
	$link->icon  	  	= 'bi bi-person-gear';
	$link->permission 	= 'logged_in';
	$childrens 			= [];
	$link->childrens 	= ActionFilterHelper::doFilter(plugin_id().'_sub_menu_links',$childrens);
	$links[] 	 	 	= $link;
	return $links;
},44);