<?php

use Propel\Runtime\ActiveQuery\Criteria;
require_once "vendor/autoload.php";
require_once "vendor/bin/generated-conf/config.php";

define('__GLOBAL_DATABASE__', true);
define('__CONFIG_GUI__', false);
define('__NODE_SERVER__', false);

define('__VERBOSE__', true);


/**
 * Starting point for the node server
 */
function node() {
	
	if(__VERBOSE__)
		echo('Running in local db mode<br>');
	
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
	$reponse = $config->handleRequest();
	echo $response;
	return;
	$path = $_SERVER['REQUEST_URI'];
	
	$path = explode('?', $path)[0];
	
	
	
	$parts = explode('/', $path);
	print_r($parts);
	if($parts[2] == 'config' && $parts[3] == 'submit' && $parts[4] == 'manageconfig') {
		
		print_r($_POST);
		
		header('Location: /SENG401/home');
		
	} else {
		$contents = file_get_contents('CacheConfigFrontend/CacheConfigGUI/ManageConfiguration.html');
		echo $contents;
	}
}

/**
 * Starting point for the global database webserver
 */
function global_db() {
	
	if(true){
		
		if(__VERBOSE__)
			echo('Running in global db mode<br>');
		
		$handler = new RESTRequestHandler();
		$response = $handler->handleRequest();
		echo ($response);
	}
	else{
		
		$request = new Request();
		$request->setProtocol('http://');
		$request->setUrlRoot('localhost/SENG401');
		$request->setApiVersion('v1');
		$request->addRequestVariable('type', 'create_rule');
		$request->addRequestVariable('localttl', 100);
		$request->addRequestVariable('globalttl', 200);
		//echo($request->__toString().'<br>');
		/*
		 * http://localhost/SENG401/v1?type=create_rule&localttl=100&globalttl=200
		 */
	
		
		$request = new Request();
		$request->setProtocol('http://');
		$request->setUrlRoot('localhost/SENG401');
		$request->setApiVersion('v1');
		$request->addRequestVariable('type', 'create_rule');
		$request->addRequestVariable('localttl', 80);
		$request->addRequestVariable('globalttl', 180);
		$matchVars = array();
		$E = array();
		$E['variable_name'] = 'type';
		$E['variable_value'] = 'locations';
		$matchVars[] = $E;
		$E['variable_name'] = 'fish';
		$E['variable_value'] = 'salmon';
		$matchVars[] = $E;
		$request->addRequestVariable('match_variables', $matchVars);
		echo($request->__toString().'<br>');
		/*
		 * http://localhost/SENG401/v1?type=create_rule&localttl=80&globalttl=180&match_variables%5B0%5D%5Bvariable_name%5D=type&match_variables%5B0%5D%5Bvariable_value%5D=locations&match_variables%5B1%5D%5Bvariable_name%5D=fish&match_variables%5B1%5D%5Bvariable_value%5D=salmon
		 */
		
		$request = new Request();
		$request->setProtocol('http://');
		$request->setUrlRoot('localhost/SENG401');
		$request->setApiVersion('v1');
		$request->addRequestVariable('type', 'create_rule');
		$request->addRequestVariable('localttl', 200);
		$request->addRequestVariable('globalttl', 180);
		$matchVars = array();
		$E = array();
		$E['variable_name'] = 'type';
		$E['variable_value'] = 'locations';
		$matchVars[] = $E;
		$E['variable_name'] = 'fish';
		$E['variable_value'] = 'salmon';
		$matchVars[] = $E;
		$request->addRequestVariable('match_variables', $matchVars);
		echo($request->__toString().'<br>');
		/*
		 * http://localhost/SENG401/v1?type=create_rule&localttl=200&globalttl=180&match_variables%5B0%5D%5Bvariable_name%5D=type&match_variables%5B0%5D%5Bvariable_value%5D=locations&match_variables%5B1%5D%5Bvariable_name%5D=fish&match_variables%5B1%5D%5Bvariable_value%5D=salmon
		 */
	}
	
	//$handler = new RESTRequestHandler();
	//$response = $handler->handleRequest();
	//echo($response);
	
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
	
	//This is just test code
	if(false){
		
		try{
			
			$cacheType = 'global';
		
			$request1 = new \CachedRequest();
			
			$request1->setQueryResponse("Hello World!");
			$request1->setQueryTime(300);
			
			$request1->save();
			
			$request2 = new \CachedRequest();
			
			$request2->setQueryResponse("OH BOI");
			$request2->setQueryTime(320);
			
			$request2->save();
			
			
			
			
			if(__VERBOSE__)
				echo('Success saving rows<br>');
			
			$query = CachedRequestQuery::create();
			$findOneResult = $query->findOne();
			
			echo('FindOneResult='.$findOneResult.'<br>');
			
			$qid = $findOneResult->getQueryId();
			
			$query->filterByQueryId($qid, Criteria::EQUAL);
			
			$query->delete();
			
			$query = CachedRequestQuery::create();
			$findOneResult = $query->findOne();
				
			echo('FindOneResult='.$findOneResult.'<br>');
				
			$qid = $findOneResult->getQueryId();
				
			$query->filterByQueryId($qid, Criteria::EQUAL);
				
			$query->delete();
			
			
			$findOneResult = $query->findOne();
			
			//echo('FindOneResult='.$findOneResult.'<br>');
			
			//$qid = $findOneResult->getQueryId();
			
			//$findOneResult->delete();
			
			//$query->filterByQueryId($qid, Criteria::NOT_EQUAL);
			
			//$findOneResult = $query->findOne();
			
			
			if($findOneResult === null)
				echo('Is null<br>');
			
			if(__VERBOSE__)
				echo('Made it to the end<br>');
			
			//$request = new \GlobalCachedRequests();
			
			//$request->setQueryResponse("Hello World!");
			//$request->save();
			
			
			//$query = GlobalCachedRequestsQuery::create();
			//$query = $query->filterByQueryResponse("Hello World!");
			//$query = $query->findOne();
					
			//$query->delete();
			
		}catch (Exception $e) {
			if(__VERBOSE__)
				echo('Exception Caught<br>');
			echo($e->getLine().'<br>'.$e->getMessage().'<br>'.$e->getTrace().'<br>');
        	$response = json_encode(array("error" => "{$e->getMessage()}"));
        }
		
	}
}


if(__GLOBAL_DATABASE__) {
	global_db();
} else if(__CONFIG_GUI__) {
	config();
} else if(__NODE_SERVER__) {
	node();
}