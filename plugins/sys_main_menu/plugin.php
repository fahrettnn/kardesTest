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

ActionFilterHelper::addAction('header_menu',function()
{
	$links = [];
    $links = ActionFilterHelper::doFilter(plugin_id().'_menu_links',$links);
	usort($links, function ($a, $b) 
	{
        return $a->id - $b->id;
    });
	ViewHelper::viewPlugin(plugin_path('views/menu_view.php'),['links' => $links]);
});