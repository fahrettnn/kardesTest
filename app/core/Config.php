<?php
namespace App\Core;

use Tracy\Debugger;

class Config
{
    protected string $minPhpVersion;
    protected bool $debugMod;

    public function __construct(string $minPhpVersion,bool $debugMod = true)
    {
        $this->minPhpVersion = $minPhpVersion;
        $this->debugMod 	 = $debugMod;
    }

    public function phpVersionControl()
	{
		if(phpversion() < $this->minPhpVersion)
    		die("You need a minimum of PHP version $this->minPhpVersion to run this app");
	}

	public function check_extensions()
	{
		// PHP.ini dosyasında açık olamsı gereken yerleri liste ekelemler yapılacak 
		$extensions = 
		[
			'gd',
			'pdo_mysql'
		];

		$not_loaded = [];
		foreach ($extensions as $ext) {
			if(!extension_loaded($ext))
				$not_loaded[] = $ext;
		}

		if(!empty($not_loaded))
			dd("please load the following extensions in your php.ini file: " . implode(",", $not_loaded));
	}

	public function root()
	{
		if((empty($_SERVER['SERVER_NAME']) && strpos(PHP_SAPI, 'cgi') !== 0) || (!empty($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost'))
		    return 'http://localhost/me-kardes-app.com.tr';
		else
		    return 'https://kardes-app.com.tr';
	}

	public function devopsMod()
	{
		if ($this->debugMod) {
			ini_set('display_errors', 1);
			Debugger::enable();
		}else{
			ini_set('display_errors', 0);
		}
	}

	public function configRun()
	{
	    $this->phpVersionControl();
	    $this->check_extensions();
	    $this->devopsMod();
	}

}