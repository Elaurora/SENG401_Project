<?php
use Base\CachedRequests;

/**
 * Autoloader for dependencies, put all the files you create in this list
 */
function import() {
	include_once 'vendor/autoload.php';
	require_once 'vendor/bin/generated-conf/config.php';
	include_once 'RequestServices/RequestController/RequestController.php';
    include_once 'RequestServices/RESTRequestHandler/RESTRequestHandler.php';
    include_once 'RequestServices/RequestExecutor/RequestExecutor.php';
	include_once 'RequestServices/RequestTranslation/RequestBuilder/RequestBuilder.php';
	include_once 'RequestServices/RequestTranslation/RequestParser/RequestParser.php';
	include_once 'RequestServices/RequestTranslation/Request.php';
    include_once 'RetrievalServices/RESTDataRetriever.php';
}

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
 * Starting point for the load balancer
 */
function global_db() {
	/*
	 * DB Schema
	 * 
	 * TABLE: cached_requests
	 * query_id : int
	 * query_response : binary dump
	 * PrimaryKey: {query_id}
	 * 
	 * 
	 * TABLE: get_variables
	 * query_id : int (foreign key on cached_requests.query_id)
	 * variable_name : string
	 * variable_value: string
	 * PrimaryKey: {query_id, variable_name}
	 * 
	 * TABLE: cache_rules
	 * rule_id : int
	 * local_ttl : int (seconds)
	 * global_ttl : int (seconds)
	 * PrimaryKey: {rule_id}
	 * 
	 * TABLE: cache_match_variables
	 * rule_id : int (foreign key on cache_rules.rule_id)
	 * variable_name : string
	 * variable_value: string
	 * PrimaryKey : {rule_id, variable_name, variable_value}
	 * 
	 */
	
	
	$request = new \CachedRequests();
	$request->setQueryResponse("Hello World!");
	$request->save();

	$query = CachedRequestsQuery::create();
	$query = $query->filterByQueryResponse("Hello World!");
	$query = $query->findOne();
	
	print_r($query->toJSON());
	
	$query->delete();
}

import();
if(__GLOBAL_DATABASE__) {
	global_db();
} else if(__CONFIG_GUI__) {
	config();
} else if(__NODE_SERVER__) {
	node();
}