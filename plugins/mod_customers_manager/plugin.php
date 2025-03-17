<?php
use \App\Core\Helpers\ActionFilterHelper;
use App\Core\Helpers\TagsManager;
use App\Core\Helpers\ViewHelper;
use App\Core\Http\Route;
use Mod\Customers\Controllers\CityController;
use Mod\Customers\Controllers\CustomersController;
use Mod\Customers\Controllers\DistrictController;

ActionFilterHelper::setValue([
	"plugin_routes" => 
	[
		"customer_management_route"  	=> "customers-managment",
		"customers_route"  				=> "customers",
		"adress_route"  				=> "adress",
		"city_route"					=> "city",
		"districts_route"				=> "districts"
	],
	"tables" => [
		"customers_Table" => "tbl_customers", 
		"adress_table" => "tbl_adress", 
		"city_table" => "tbl_city", 
		"disc_table" => "tbl_disctints", 
	],
]);

ActionFilterHelper::addFilter('permissions',function($permissions)
{
    $permissions[] = 'müşterileri_görüntüleme';
    $permissions[] = 'müşteri_ekleme';
	$permissions[] = 'müşteri_düzenleme';
    $permissions[] = 'müşteri_silme';
    return $permissions;
});

ActionFilterHelper::addAction('api',function()
{
	Route::get('api/ps/city', [CityController::class, 'getCityList']);
	Route::get('api/ps/discrits', [DistrictController::class, 'getCityIdDiscritList']);


	Route::get('api/customers', [CustomersController::class, 'getList']);

});

ActionFilterHelper::addAction('controller',function()
{
    $vars = ActionFilterHelper::getValue();
	$routes = $vars["plugin_routes"];
	
});

ActionFilterHelper::addAction('view',function()
{
	$routes 	= ActionFilterHelper::getValue("plugin_routes");

	if(Route::is($routes["customer_management_route"].'/'.$routes["customers_route"]))
		ViewHelper::viewPlugin(plugin_path('views/view.php'));
});

ActionFilterHelper::addAction('header_headsTags',function()
{
	$routes 	 = ActionFilterHelper::getValue("plugin_routes");
	$headManager = new TagsManager();
	$headManager->addStyle(ROOT.'/public/assets/libs/jquery/datatable/css/jquery.dataTables.min.css',true);
	if(Route::is($routes["customer_management_route"].'/'.$routes["customers_route"]))
	{
		$headManager->addTag(tag:'title', content:__lang("customers_transactions") . ' | '. APP_NAME);
	}
	$headManager->render();

});

ActionFilterHelper::addAction('footer_scriptsTags',function()
{
	$routes 	= ActionFilterHelper::getValue("plugin_routes");
	
	if(Route::is($routes["customer_management_route"]."/".$routes["customers_route"]))
	{
		$headManager = new TagsManager();
		$headManager->addScript(ROOT. '/public/assets/libs/jquery/datatable/js/jquery.dataTables.min.js',true);
		$headManager->addScript(ROOT. '/public/assets/libs/jquery/datatable/js/datatables.init.js',true);
		$headManager->addScript('https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js',true);	
		$headManager->addScript(plugin_http_path('assets/js/customers.js'),true);
		$headManager->render();
	}
});

ActionFilterHelper::addFilter('main-menu_menu_links',function($links)
{
	$routes 			= ActionFilterHelper::getValue("plugin_routes");
	$link 		 	  	= (object)[];
	$link->id 	 	  	= 1;
	$link->title 	  	= __lang("customers_transactions");
	$link->slug  	 	= $routes["customer_management_route"];
	$link->icon  	  	= 'bi bi-building-check';
	$link->permission 	= 'logged_in';
	$childrens 			= [];
	$link->childrens 	= ActionFilterHelper::doFilter(plugin_id().'_sub_menu_links',$childrens);
	$links[] 	 	 	= $link;
	return $links;
},44);

ActionFilterHelper::addFilter('customer-manager_sub_menu_links',function($links)
{
	$routes = ActionFilterHelper::getValue("plugin_routes");
	$link 		 	  = (object)[];
	$link->id 	 	  = 1;
	$link->title 	  = __lang("customers_transactions");
	$link->slug  	  = $routes["customers_route"];
	$link->icon  	  = 'bi bi-users';
	$link->permission = 'logged_in';
	$links[] 	 	 = $link;
	return $links;
},1);