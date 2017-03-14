<?php
/**
 * Autoloader for dependencies, put all the files you create in this list
 */
function import() {
	include_once 'RequestServices/RequestController/RequestController.php';
    include_once 'RequestServices/RESTRequestHandler/RESTRequestHandler.php';
    include_once 'RequestServices/RequestExecutor/RequestExecutor.php';
	include_once 'RequestServices/RequestTranslation/RequestBuilder/RequestBuilder.php';
	include_once 'RequestServices/RequestTranslation/RequestParser/RequestParser.php';
	include_once 'RequestServices/RequestTranslation/Request.php';
    include_once 'RetrievalServices/RESTDataRetriever.php';
}

define('__LOAD_BALANCER__', false);
define('__CONFIG_GUI__', false);
define('__NODE_SERVER__', true);


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