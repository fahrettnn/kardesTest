<?php
use \App\Core\Helpers\ActionFilterHelper;
use App\Core\Helpers\ViewHelper;
use App\Core\Models\Session;

ActionFilterHelper::addAction('header_navheader',function()
{
	ViewHelper::viewPlugin(plugin_path('views/navheader_view.php'));
});

ActionFilterHelper::addAction('header_header',function()
{
	$ses = new Session;
	ViewHelper::viewPlugin(plugin_path('views/header_view.php'),['getUser' => $ses->getUser()]);
});