<?php

/**
 * Plugin Name: Questpass
 * Description: Questpass plugin displays ads based on the idea of questvertising.
 * Version: 2.0.3
 * Author: Questpass
 * Author URI: https://questpass.pl/
 * Text Domain: questpass
 */

define( 'QUESTPASS_DEFAULT_API_TOKEN', '__DEFAULT_API_TOKEN__' );
define( 'QUESTPASS_DEFAULT_API_CLIENT', '__DEFAULT_API_CLIENT__' );
define( 'QUESTPASS_DEFAULT_API_SECRET', '__DEFAULT_API_SECRET__' );

require_once __DIR__ . '/vendor/autoload.php';

new Questpass\Questpass(
	new Questpass\PluginInfo( __FILE__, '2.0.3' )
);
