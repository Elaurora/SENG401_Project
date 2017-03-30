<?php

define('__GLOBAL_DATABASE__', true);
define('__CONFIG_GUI__', false);
define('__NODE_SERVER__', false);


/**
 * Starting point for the node server
 */
function node() {
	/* scratch test to make sure this thing works a little. - Andy
	 * feel free 2 erase*/
	$handler = new RESTRequestHandler();
	$response = $handler -> handleRequest();
	echo ($response);
	
}

/**
 * Starting point for the config GUI
 */
function config() {
	
}

/**
 * Starting point for the global database webserver
 */
function global_db() {
	node();
}


if(__GLOBAL_DATABASE__) {
	global_db();
} else if(__CONFIG_GUI__) {
	config();
} else if(__NODE_SERVER__) {
	node();
}