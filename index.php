<?php




define('__LOAD_BALANCER__', false);
define('__CONFIG_GUI__', false);
define('__NODE_SERVER__', true);


/**
 * Starting point for the node server
 */
function node() {
	
}

/**
 * Starting point for the config GUI
 */
function config() {
	
}

/**
 * Starting point for the load balancer
 */
function load() {
	
}


/**
 * Autoloader for dependencies
 */
function import() {
	
}

import();
if(__LOAD_BALANCER__) {
	load();
} else if(__CONFIG_GUI__) {
	config();
} else if(__NODE_SERVER__) {
	node();
}