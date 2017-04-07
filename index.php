<?php
require_once "vendor/autoload.php";
require_once "vendor/bin/generated-conf/config.php";

define('__GLOBAL_DATABASE__', false);
define('__CONFIG_GUI__', false);
define('__NODE_SERVER__', true);

define('__VERBOSE__', false);


/**
 * Starting point for the node server
 */
function node() {
	/* scratch test to make sure this thing works a little. - Andy
	 * feel free 2 erase*/
	$handler = new RESTRequestHandler();
	$response = $handler->handleRequest();
	echo ($response);
}

/**
 * Starting point for the config GUI
 */
function config() {
	$config = new CacheConfigController();
	$response = $config->handleRequest();
	echo $response;
}

/**
 * Starting point for the global database webserver
 */
function global_db() {
	node();
	/*
	 * DB Schema
	 * 
	 * TABLE: cached_requests
	 * query_id : int
	 * query_url : string
	 * query_response : binary dump
	 * query_time (Unix Timestamp)
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
	
}


if(__GLOBAL_DATABASE__) {
	global_db();
} else if(__CONFIG_GUI__) {
	config();
} else if(__NODE_SERVER__) {
	node();
}