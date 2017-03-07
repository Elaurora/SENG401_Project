<?php
/**
 * Autoloader for dependencies, put all the files you create in this list
 */
function import() {
	include_once 'RequestServices/RequestTranslation/RequestBuilder/RequestBuilder.php';
	include_once 'RequestServices/RequestTranslation/RequestBuilder/RequestGroup.php';
	include_once 'RequestServices/RequestTranslation/RequestParser/RequestParser.php';
	include_once 'RequestServices/RequestTranslation/Request.php';
}

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

import();
if(__LOAD_BALANCER__) {
	load();
} else if(__CONFIG_GUI__) {
	config();
} else if(__NODE_SERVER__) {
	node();
}