<?php
class DATABASE_CONFIG {

	var $development = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => '',
		'database' => 'brasilopen',
		'prefix' => 'site_',
		'encoding' => 'utf8',
	);

	var $production = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'gillette_root',
		'password' => 'QM;Hh9Bp*8%4',
		'database' => 'gillette_site',
		'prefix' => 'site_',
		'encoding' => 'utf8',
	);
	/*

	var $production = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'brasilop',
		'password' => '4dhxssntx7c',
		'database' => 'brasilop_site',
		'prefix' => 'site_',
		'encoding' => 'utf8',
	);
	*/
	var $default = array();
	
	function __construct(){
        $this->default = strstr($_SERVER['SERVER_NAME'], 'localhost')  ? 
			$this->development : $this->production;
		
		//Tradução database
		$this->translated = $this->default;
		$this->translated['database'] = sprintf(Configure::read('Config.language_options.database'), $this->translated['database']);
    }
	
	function DATABASE_CONFIG(){
		$this->__construct();
	}
}
