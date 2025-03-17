<?php
use \App\Core\Helpers\ActionFilterHelper;
use \App\Core\Database\Connection;
use App\Core\Helpers\TagsManager;
use \App\Core\Helpers\UrlHelper;
use App\Core\Helpers\ViewHelper;
use App\Core\Http\Route;
use Mod\Customers\Controllers\CustomersController;

/**
 * Plugin Dev : Lovesta Inc.
 * Plugin Id  : plugin-sample
 * Plugin Name: Sample Plugin
 * Plugin Desc: Sample Plugin for Lovesta Inc.
 */
ActionFilterHelper::setValue([
	"plugin_routes" => 
	[
		"customer_management_route" => "customers-managment",
		"contact_book_route"  		=> "contact-book",
	]
]);

/** set user permissions for this plugin **/
ActionFilterHelper::addFilter('permissions',function($permissions)
{
    $permissions[] = 'iletişim_defteri_görüntüleme';
    $permissions[] = 'iletişim_defteri_ekleme';
	$permissions[] = 'iletişim_defteri_düzenleme';
    $permissions[] = 'iletişim_defteri_silme';
    return $permissions;
});

ActionFilterHelper::addAction('api',function()
{
	Route::get('api/contact-book', [CustomersController::class, 'getList']);
	Route::post('api/contact-book', [CustomersController::class, 'getList']);
	Route::put('api/contact-book', [CustomersController::class, 'getList']);
	Route::delete('api/contact-book', [CustomersController::class, 'getList']);
});

ActionFilterHelper::addAction('view',function()
{
	$vars = ActionFilterHelper::getValue();
	$routes = $vars["plugin_routes"];
	if(Route::is($routes["customer_management_route"].'/'.$routes["contact_book_route"]))
		ViewHelper::viewPlugin(plugin_path('views/view.php'));
});

ActionFilterHelper::addAction('header_headsTags',function()
{
	$routes 	 = ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();
	if(Route::is($routes["customer_management_route"].'/'.$routes["contact_book_route"]))
		$headManager->addTag(tag:'title', content:'İletişim Defteri | '.APP_NAME);
	$headManager->render();
});

ActionFilterHelper::addAction('footer_scriptsTags',function()
{
	$routes 	= ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();
	if(Route::is($routes["customer_management_route"].'/'.$routes["contact_book_route"]))
		$headManager->addScript(ROOT.'< Enter path Url >',true)->addScript(plugin_http_path('< Enter path Url >'),true);
	
	$headManager->render();
});

ActionFilterHelper::addFilter('customer-manager_sub_menu_links',function($links)
{
	$routes = ActionFilterHelper::getValue("plugin_routes");
	$link 		 	  = (object)[];
	$link->id 	 	  = 1;
	$link->title 	  = "İletişim Defteri";
	$link->slug  	  = $routes["contact_book_route"];
	$link->icon  	  = 'bi bi-users';
	$link->permission = 'logged_in';
	$links[] 	 	 = $link;
	return $links;
},2);