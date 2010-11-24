<?php

$config = array(

	'dir' => array(
		'www' => realpath(SYS_DIR.'/www'),
		'templates' => realpath(APP_DIR.'/templates'),
		'lang' => realpath(APP_DIR.'/lang'),
	),

	'url' => array(
		'www' => 'http://'.DOMAIN.'/deployer2/www',
		'images' => 'http://'.DOMAIN.'/deployer2/www/images',
		'css' => 'http://'.DOMAIN.'/deployer2/www/css',
		'js' => 'http://'.DOMAIN.'/deployer2/www/js',
	),

	'default_lang' => 'NL',
	
);