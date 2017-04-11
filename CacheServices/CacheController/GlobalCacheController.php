<?php

use Propel\Runtime\ActiveQuery\Criteria;
use Base\GlobalCacheMatchVariableQuery;
use Base\GlobalCacheRule;
class GlobalCacheController extends CacheController{
	
	/**
	 * Last resort default Ttl for caches. 120 seconds.
	 * @var integer
	 */
	public static $defaultTtl = 600;
	
	/**
	 * Figures out if the given request exists within the cache, and returns it if does exist and has not expired.
	 * If the request is found in the cache but it is found to be expired, the request will be deleted from the cache and the function
	 * will return a 'status' of 'failure'
	 * @param Request $request - the request to check the cache for
	 * @return An array containing the response for the cached request
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	public function getCachedRequest(Request $request){
		//see if the request url matches an existing query_url in the table
		$query = \GlobalCachedRequestQuery::create()->findOneByQueryUrlRoot($request->__toString());
	
		if(!isset($query)){
			// if nothing matches, return false
			$this->incrementCacheMissCounter();
			return false;
			
		}
		
		//load all the rules and their variables
		$cacheRuleQuery = \GlobalCacheRuleQuery::create()->find();
		
		//the array for saving rules that match
		$matchedRules = array();
		
		//iterate over each rule and see how much it matches
		foreach($cacheRuleQuery as $cacheRule) {
			$overlap = $this->computeVariableSetOverlap($query->getGlobalGetVariables(), $cacheRule->getGlobalCacheMatchVariables());
			
			if($overlap >= 0) {
				$matchedRules[$cacheRule->getRuleId()] = $overlap;
			}
		}
		
		//we found at least one relevant rule #
		if(count($matchedRules) > 0) {
			//find the rule that matched best
			$ruleID = array_search(max($matchedRules), $matchedRules);
			
			$ttl = \GlobalCacheRuleQuery::create()->findOneByRuleId($ruleID)->getGlobalTtl();
		} else {
			// if no rule in table, use another default value in this class
			$ttl = GlobalCacheController::$defaultTtl;
		}
		
		
		//check that the query_time+local_ttl is greater than the current time		
		if($query->getQueryTime() + $ttl > mktime()){
			//if it is, return the query_response
			$this->incrementCacheHitCounter();
			return hex2bin($query->getQueryResponse());
		}
		else{
			//if it isn't, delete the cached request, return false
			//First delete all the related variables in get_variables
			$query->getGlobalGetVariables()->delete();		
			$query->delete();
			$this->incrementCacheMissCounter();
			return false;
		}
	}
	
	/**
	 * fixes the problem of calling add[global]GetVariables
	 * in cacheRequest. This feels somewhat sloppy perhaps.
	 * @param unknown $storedRequest the new entry in cached_requests
	 * @param unknown $variable the GetVariable object to be stored for the cached request
	 *
	 */
	protected function addGetVariablesForCache($storedRequest, $variable){
		$storedRequest->addGlobalGetVariable($variable);
	}
	
	/**
	 * Increments the number of misses for the global cache
	 */
	protected function incrementCacheMissCounter(){
		
		// Query the one Row in this table. if it doesn't exist, create it.
		$record = GlobalCacheHitRecordQuery::create();
		$record = $record->filterByRecordId(CacheController::$recordKey);
		$record = $record->findOne();
		if(!isset($record)){
			// create dat new entry
			$newRecord = new \GlobalCacheHitRecord();
			$newRecord->setPrimaryKey(CacheController::$recordKey);
			$newRecord->setMissCount(1);
			$newRecord->setHitCount(0);
			$newRecord->save();
		}
		else{
			// Otherwise, update the existing row.
			$missCount = $record->getMissCount();
			$missCount += 1;
			$record->setMissCount($missCount);
			$record->save();
		}
	}
	
	/**
	 * Increments the number of misses for the global cache
	 */
	protected function incrementCacheHitCounter(){
		// Query the one Row in this table. if it doesn't exist, create it.
		$record = GlobalCacheHitRecordQuery::create();
		$record = $record->filterByRecordId(CacheController::$recordKey);
		$record = $record->findOne();
		if(!isset($record)){
			// create dat new entry
			$newRecord = new \CacheHitRecord();
			$newRecord->setPrimaryKey(CacheController::$recordKey);
			$newRecord->setMissCount(0);
			$newRecord->setHitCount(1);
			$newRecord->save();
		}
		else{
			// Otherwise, update the existing row.
			$hitCount = $record->getHitCount();
			$hitCount += 1;
			$record->setHitCount($hitCount);
			$record->save();
		}
	}
	
	/**
	 * Get a cache request object for the corresponding database type
	 */
	protected function createCachedRequest(){
		return new \GlobalCachedRequest();
	}
	
	/**
	 * Create a GetVariable object for the corresponding database type
	 */
	protected function createGetVariable(){
		return new \GlobalGetVariable();
	}
	
	/**
	 * Create a CacheHitRecord object for the global database
	 */
	protected function createCacheHitRecord(){
		return new \GlobalCacheHitRecord();
	}
	
	/**
	 * Gets a cached requests query corresponding to the global database
	 */
	protected function getCachedRequestsQuery(){
		return GlobalCachedRequestQuery::create();
	}
	
	/**
	 * Gets a cached requests query corresponding to the global database
	 */
	protected function getCacheHitRecordQuery(){
		return GlobalCacheHitRecordQuery::create();
	}
	
	/**
	 * Gets a cache rule query corresponding to the Global database
	 */
	protected function getCacheRuleQuery(){
		return GlobalCacheRuleQuery::create();
	}
	
	
	/**
	 * Gets a cache match variables query for the corresponding database type
	 */
	protected function getCacheMatchVariablesQuery(){
		return GlobalCacheMatchVariableQuery::create();
	}
	
	/**
	 * Gets a get variables query for the corresponding database type
	 */
	protected function getVariablesQuery(){
		return GlobalGetVariableQuery::create();
	}
	
	/**
	 * Gets all the 'GET' variabled for a cached request
	 * @param ChildCachedRequest[]|ObjectCollection
	 */
	protected function getGetVariables($query) {
		return $query->getGlobalGetVariables();
	}
	
	/**
	 * gets all GlobalCacheMatchVariables with a foreign key matching the given rule
	 * @param unknown $rule the rule to get all CacheMatchVariables for
	 */
	protected function getCacheMatchVariables($rule){
		return $rule->getGlobalCacheMatchVariables();
	}
	
	/**
	 * Creats a new rule in the cache using the given variables
	 * @param unknown $variables - an array containing the indicies: 'localttl' , 'globalttl' , 'match_variables'
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	protected function createRule($variables){
		$response = parent::createRule($variables);
		if($response !== null) {
			return $response;
		}
		
		if(isset($variables['rule_id'])){
			$response['status'] = 'failure';
			$response['errmsg'] = 'Attempted to create a rule in a GlobalCache by specifying the rule_id. The global cache will auto-increment rule_ids';
			return $response;
		}
		
		if(!isset($variables['match_variables'])) {
			$response['status'] = 'failure';
			$response['errmsg'] = 'Attempted to create a rule in a GlobalCache without specifying the match variables';
			return $response;
		}
		
		//parse out the variables we need
		$matchVariables = json_decode($variables['match_variables'], true);
		$localttl = $variables['localttl'];
		$globalttl = $variables['globalttl'];
		
		$ruleToEdit = $this->getMatchingRule($matchVariables);
		
		//If i did not find a matching rule, i will need to create a new one as well as new CacheMatchVariables
		if($ruleToEdit === null){
			$ruleToEdit = new \GlobalCacheRule();
			
			foreach($matchVariables as $name => $value){
				$newMatchVar = new \GlobalCacheMatchVariable();
				$newMatchVar->setVariableName($name);
				$newMatchVar->setVariableValue($value);
				$ruleToEdit->addGlobalCacheMatchVariable($newMatchVar);
			}
			
		} 
		
		
		
		$ruleToEdit->setLocalTtl($localttl);
		$ruleToEdit->setGlobalTtl($globalttl);
		
		
		$ruleToEdit->save();
		
		
		//setup the request runner
		$retriever = new RequestDataRetriever();
		
		//establish the basic request
		$request = new Request();
		$request->setProtocol('http://');
		$request->setApiVersion('v1');
			
		$request->addRequestVariable('type', 'create_rule');
		$request->addRequestVariable('localttl', $localttl);
		$request->addRequestVariable('globalttl', $globalttl);
		$request->addRequestVariable('match_variables', $variables['match_variables']);
		$request->addRequestVariable('rule_id', $ruleToEdit->getRuleId());
		
		
		//The global cache now needs to inform all of its subscribers about the new rule
		$subscribers = \GlobalSubscriberIpQuery::create()->find();
		foreach($subscribers as $subscriber){
			//point the request at a new subscriber
			$request->setUrlRoot($subscriber->getSubscriberIp().'/SENG401');//the extra /SENG401 is okay, because the parser only looks at get variables for cache rules
		
			$retriever->completeRequest($request);
		}
		
		$response = array(
			'status' => 'success',
		);
		return $response;
	}
	
	/**
	 * Finds and returns a GlobalCacheRule that matches exactly on all match variables
	 * @param string[] $matchVariables
	 * 		An associative array of varName => varValue
	 * @return GlobalCacheRule
	 * 		The rule that matches the variable exactly, returns null if there is no match
	 */
	protected function getMatchingRule($matchVariables) {
		$rules = \GlobalCacheRuleQuery::create()
					->joinWithGlobalCacheMatchVariable(Criteria::LEFT_JOIN)
					->find();
		
		//iterate over each rule and check if it matches the $matchVariables exactly
		foreach($rules as $rule) {	
			if($this->checkRuleMatchesVariables($rule, $matchVariables)) {
				return $rule;
			}	
		}
		
		return null;
	}
	
	/**
	 * Checks to see if $matchVariables exactly matches the match variables on $rule
	 * @param GlobalCacheRule $rule
	 * 		The rule that we are checking for matches on
	 * @param string[] $matchVariables
	 * 		An associative array of varName => varValue
	 * @return bool
	 * 		true if the sets match, false otherwise
	 */
	protected function checkRuleMatchesVariables($rule, $matchVariables) {
		//to be efficient, this computation can be done with a hashmap
		//each variable in $rule will be inserted to the map
		//and then each variable in $matchVariables will be removed from the hashmap
		//if there are any left or if a variable is removed that wasn't there, they don't match
		$variableMap = array();
		
		//insert to the map
		$ruleVariables = $rule->getGlobalCacheMatchVariables();
		foreach($ruleVariables as $ruleVariable) {
			$variableMap[$ruleVariable->getVariableName()] = $ruleVariable->getVariableValue();
		}
		
		//remove from the map
		foreach($matchVariables as $name => $value) {
			
			//if the variable we are trying to remove isn't in the map, the sets don't match, return false
			if(!isset($variableMap[$name])) {
				return false;
			}
			
			if($variableMap[$name] != $value) {
				return false;
			}
			
			unset($variableMap[$name]);	
		}
		
		//if there are any variables left in the map, they don't match
		if(count($variableMap) > 0) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Deletes the rule with the given rule_id from the cache. If this is the global cache, it will also delete the rule from all subscribed caches
	 * @param $variables an array containing the index 'rule_id' which indicates which rule is to be deleted
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	protected function deleteRule($variables){
		$response = parent::deleteRule($variables);
		
		if($response['status'] != 'success'){
			return $response;
		}
		
		// now do the same for all the little kiddies
		$allSubs = \GlobalSubscriberIpQuery::create()->find();
		
		$request = new Request();
		$request->setProtocol('http://');
		
		$request->setApiVersion('v1');
		$request->addRequestVariable('type', 'delete_rule');
		$request->addRequestVariable('rule_id', $variables['rule_id']);
		
		foreach($allSubs as $sub){
			//point at the correct sub
			$request->setUrlRoot($sub->getSubscriberIp().'/SENG401');
			
			$url = $request->__toString();
			
			$localCacheResponse = file_get_contents($url);
			
			if($localCacheResponse['status'] == 'failure'){
				$response['status'] = 'failure';
				$response['errmsg'] = $localCacheResponse['errmsg'];
			}
			else if($localCacheResponse['status'] == 'success'){	
				$response['status'] = 'success';
			}
		}
		

		$response['status'] = 'success';
		return $response;
	}
	
	
	/**
	 * Sets the hit and miss counters of a cache to 0, and clears all saved requests.
	 * @param unknown $clearType clear_locals, clear_global, or clear_all.
	 */
	protected function clearCache($clearType){
		
		// Check the flavor of the Clearing request, and pass on instructions
		// to subscribers to clear their caches as appropriate.
		if ($clearType == 'clear_locals' || $clearType == 'clear_all'){
			//Clear the local caches.
			
			// THIS IS NOT TESTED.
			$allSubs = \GlobalSubscriberIpQuery::create()->find();
			
			foreach($allSubs as $sub){
				$request = new Request();
				$request->setProtocol('http://');
				$request->setUrlRoot($sub->getSubscriberIp().'/SENG401');
				$request->setApiVersion('v1');
				$request->addRequestVariable('type', 'clear_locals');
				
				$url = $request->__toString();

				$localCacheResponse = file_get_contents($url);
				
				if($localCacheResponse['status'] == 'failure'){

				}
				else if($localCacheResponse['status'] == 'success'){
					
				}else{

				}
				
			}
			
		}
		
		if ($clearType == 'clear_global' || $clearType == 'clear_all'){
			//Clear this global Cache
			$this->clear();
		}
	}
	
	/**
	 * Returns the number of cache hits and misses.
	 * Locally, just return the number of hits and misses.
	 * Globally, return a list the hits and misses of a node together with the IP of that node.
	 */
	protected function getHits(){
		xdebug_break();
		// Create a lil Results thingy
		$result = array();
		
		//First, get number of hits for all the nodes.
		$allSubs = \GlobalSubscriberIpQuery::create()->find();
		
		foreach($allSubs as $sub){
			$request = new Request();
			$request->setProtocol('http://');
			$request->setUrlRoot($sub->getSubscriberIp().'/SENG401');
			$request->setApiVersion('v1');
			$request->addRequestVariable('type', 'get_hits');
			
			$url = $request->__toString();
			
			$localCacheResponse = file_get_contents($url);
			if (isset($localCacheResponse)) {
				$localCacheResponse = json_decode($localCacheResponse, true);
				if (isset($localCacheResponse['HitCount']) && isset($localCacheResponse['RecordId'])) {
					//dont u judge me i'm just bein practical.
					$result[$sub->getSubscriberIp()] = $localCacheResponse;
				}
			}
			
		}
		
		// Next, get the hits for this thing.
		$record = GlobalCacheHitRecordQuery::create();
		$record = $record->filterByRecordId(CacheController::$recordKey);
		$record = $record->findOne();
		if(!isset($record)){
			// create dat new entry
			$newRecord = new \GloablCacheHitRecord();
			$newRecord->setPrimaryKey(CacheController::$recordKey);
			$newRecord->setMissCount(0);
			$newRecord->setHitCount(0);
			$newRecord->save();
			$result['Global'] = $newRecord->toArray();
		}
		else {
			$result['Global'] = $record->toArray();
		}
		$result['status'] = 'success';
		return $result;
	}
	
	/**
	 * Adds the senders ip to the list of subscibers to the GlobalCache
	 * Will also add all currently cached rules to the new subscriber
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	protected function subscribe(){
		$subscriberIP = $this->getSenderIp();
		
		$toBeAdded = $subQuery = GlobalSubscriberIpQuery::create()
			->filterBySubscriberIp($unsubscriberIP)
			->findOne();
		
		if($toBeAdded != null){
			$response['status'] = 'failure';
			$response['errmsg'] = 'Could not add '.$subscriberIP.' to the database because it is already subscribed';
			return $response;
		}
		
		//Initialize and save the new subscriber into the database
		$subscriber = new \GlobalSubscriberIp();
		$subscriber->setSubscriberIp($subscriberIP);
		$subscriber->save();


		//Implemented but not tested
		$response = $this->getAllRules();
		$response['status'] = 'success';
		return $response;
	}
	
	/**
	 * Removes the senders ip from the list of subscibers to the GlobalCache
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	protected function unsubscribe(){
		$response = array();
		
		$unsubscriberIP = $this->getSenderIp();
		
		$subQuery = GlobalSubscriberIpQuery::create()
			->filterBySubscriberIp($unsubscriberIP);
		
		$ToBeDeleted = $subQuery->findOne();
		
		if($ToBeDeleted !== null){
			$subQuery->delete();
			$response['status'] = 'success';
		}else{
			$response['status'] = 'failure';
			$response['errmsg'] = 'Tried to unsubscribe an IP address that was not subscribed.';
		}
		
		//Implemented but not tested
		
		return $response;
	}
}