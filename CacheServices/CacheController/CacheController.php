<?php
abstract class CacheController{
	
	/**
	 * An array containing all the different rule types that can be executed on a cache
	 * @var array
	 */
	public static $ruleTypes = array (
			'create_rule', 'get_rules', 'delete_rule', 'subscribe', 'unsubscribe',
			'clear_locals', 'clear_global', 'clear_all'
	);
	
	/**
	 * Default key for the single row in the cache_hit_record table
	 * @var integer
	 */
	protected static $recordKey = 1;
	
	
	
	
	/**
	 * Figures out if the given request exists within the cache, and returns it if does exist and has not expired.
	 * If the request is found in the cache but it is found to be expired, the request will be deleted from the cache and the function
	 * will return a 'status' of 'failure'
	 * @param Request $request - the request to check the cache for
	 * @return An array containing the response for the cached request
	 * @return The array will also contain a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	public abstract function getCachedRequest(Request $request);
	
	/**
	 * Accepts two sets of GET variable database rows, computes whether they overlap and by how much
	 * 
	 * @param GetVariable[] $requestSet
	 * @param GetVariable[] $ruleSet
	 * 
	 * @return int
	 * 		-1 for no overlap
	 * 		otherwise, the number of overlapping variables
	 */
	protected function computeVariableSetOverlap($requestSet, $ruleSet) {
		//if there is anything in the rule set that isn't in the request set, automatic failure, other than that we are just counting matches
		$matchSum = 0;
		foreach($ruleSet as $ruleVar) {
			$foundMatchInRequestSet = false;
			
			foreach($requestSet as $requestVar) {
				if($requestVar->getVariableName() == $ruleVar->getVariableName()) {
					if($requestVar->getVariableValue() == $ruleVar->getVariableValue()) {
						$foundMatchInRequestSet = true;
						$matchSum++;
					}
				}
			}
			
			if(!$foundMatchInRequestSet) {
				return -1;
			}
		}
		
		return $matchSum;
	}
	
	/**
	 * Adds the given request to the cache.
	 * @param unknown $request the request to add to the cache
	 * @param string $response the response of the given request to be cached.
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	public function cacheRequest(Request $request, $response){
		$storedRequest = $this->createCachedRequest();
		$storedRequest->setQueryUrlRoot($request->__toString());
		$storedRequest->setQueryTime(mktime());
		$storedRequest->setQueryResponse(bin2hex($response));
		
		foreach ($request->getRequestVariables() as $key => $value){
			$getVars = $this->createGetVariable();
			$getVars->setVariableName($key);
			$getVars->setVariableValue($value);
			$this->addGetVariablesForCache($storedRequest, $getVars);
			//$storedRequest->addGlobalGetVariable($getVars);
		}
		
		$storedRequest->save();
	}
	
	/**
	 * fixes the problem of calling add[global]GetVariables
	 * in cacheRequest. This feels somewhat sloppy perhaps.
	 * @param unknown $storedRequest the new entry in cached_requests
	 * @param unknown $variable the GetVariable object to be stored for the cached request
	 * 
	 */
	protected abstract function addGetVariablesForCache($storedRequest, $variable);
	
	/**
	 * Increments the number of misses for the cache
	 */
	protected abstract function incrementCacheMissCounter();
	
	/**
	 * Increments the number of misses for the cache
	 */
	protected abstract function incrementCacheHitCounter();
	
	/**
	 * Create a cache request object for the corresponding database type
	 */
	protected abstract function createCachedRequest();
	
	/**
	 * Create a GetVariable object for the corresponding database type
	 */
	protected abstract function createGetVariable();
	
	/**
	 * Create a CacheHitRecord object for the corresponding database
	 */
	protected abstract function createCacheHitRecord();
	
	/**
	 * Gets a cached requests query corresponding to the corresponding database
	 */
	protected abstract function getCachedRequestsQuery();
	
	/**
	 * Gets a cached requests query corresponding to the corresponding database
	 */
	protected abstract function getCacheHitRecordQuery();
	/**
	 * Gets a cache rule query corresponding to the corresponding database type
	 */
	protected abstract function getCacheRuleQuery();
	
	/**
	 * Gets a cache match variables query for the corresponding database type
	 */
	protected abstract function getCacheMatchVariablesQuery();
	
	/**
	 * Gets a get variables query for the corresponding database type
	 */
	protected abstract function getVariablesQuery();
	
	/**
	 * gets all CacheMatchVariables of the database Type with a foreign key matching the given rule
	 * @param unknown $rule the rule to get all CacheMatchVariables for
	 */
	protected abstract function getCacheMatchVariables($rule);
	
	/**
	 * Creats a new rule in the cache using the given variables
	 * @param unknown $variables - an array containing the indicies: 'localttl' , 'globalttl' , 'match_variables' and if it is a local cache, a 'rule_id' index.
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information 
	 */
	protected function createRule($variables) {
		$response = array();
		
		if(!isset($variables['localttl'])){
			$response['status'] = 'failure';
			$response['errmsg'] = 'Attempted to create a new rule without specifying a localttl';
			return $response;
		}
		
		if(!isset($variables['globalttl'])){
			$response['status'] = 'failure';
			$response['errmsg'] = 'Attempted to create a new rule without specifying a globalttl';
			return $response;
		}
		
		return null;
	}
	
	
	/**
	 * Gets all rules currently in the cache and returns them
	 * @return An array containing the 'rule_id' , 'localttl' , 'globalttl' and 'match_variables' which is an array containing a variable_value for each 'variable_name'
	 * @return 
	 */
	protected function getAllRules(){
		$response = array();
		
		$response['rules'] = array();
		
		$ruleQuery = $this->getCacheRuleQuery();
		
		$allRules = $ruleQuery->find();
		
		foreach($allRules as $rule){
			$rule_id = $rule->getRuleId();
			$response['rules'][$rule_id] = array();
			
			$response['rules'][$rule_id]['local_ttl'] = $rule->getLocalTtl();
			$response['rules'][$rule_id]['global_ttl'] = $rule->getGlobalTtl();
			
			$allMatchVars = $this->getCacheMatchVariables($rule);
			
			foreach($allMatchVars as $matchVar){
				if(!isset($response['rules'][$rule_id]['match_variables']))
					$response['rules'][$rule_id]['match_variables'] = array();
				
				$valuesToAdd = array();
				
				$valuesToAdd['variable_name'] = $matchVar->getVariableName();
				$valuesToAdd['variable_value'] = $matchVar->getVariableValue();
				
				$response['rules'][$rule_id]['match_variables'][] = $valuesToAdd;
			}
			
		}
		$response['status'] = 'success';
		
		return $response;
	}
	
	/**
	 * Deletes the rule with the given rule_id from the cache. If this is the global cache, it will also delete the rule from all subscribed caches
	 * @param $variables an array containing the index 'rule_id' which indicates which rule is to be deleted
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	protected function deleteRule($variables){
		$response = array();
		if(!isset($variables['rule_id'])){
			$response['status'] = 'failure';
			$response['errmsg'] = 'Attempted to delete a rule without specifying a rule_id';
			return $response;
		}
			
		$cacheRuleQuery = $this->getCacheRuleQuery();
	
		$cacheRuleQuery->filterByRuleId(intval($variables['rule_id']), Criteria::EQUAL);
		
		$findOneResult = $cacheRuleQuery->findOne();
		
		if($findOneResult === null){
			$response['status'] = 'failure';
			$response['errmsg'] = 'Attempted to delete a rule that did not exist in the database';
			return $response;
		}
		
		$cacheMatchVarsQuery = $this->getCacheMatchVariablesQuery();
		
		//First, delete all match variables with that rule id
		
		$cacheMatchVarsQuery->filterByRuleId(intval($variables['rule_id']), Criteria::EQUAL);
		$cacheMatchVarsQuery->deleteAll();
		
		$cacheRuleQuery->delete();
		
		$response['status'] = 'success';
		
		//Implemented but not tested
		
		return $response;
	}
	
	/**
	 * Decides which caches should be cleared.
	 * @param unknown $clearType clear_locals, clear_global, or clear_all.
	 */
	protected abstract function clearCache($clearType);
	
	/**
	 * Sets the hit and miss counters of the cache to 0, and clears all saved requests.
	 */
	protected function clear(){
		$response = array();
		
		// delete all the related variables
		$cacheVariableQuery = $this->getVariablesQuery();
		$cacheVariableQuery->deleteAll();
		
		// delete all the cached requests
		$cacheRequestsQuery = $this->getCachedRequestsQuery();
		$cacheRequestsQuery->deleteAll();
		
		// delete all the one rows in this table
		$record = $this->getCacheHitRecordQuery();
		$record = $record->deleteAll();
		
		// set hits and misses to 0 by just making a new entry
		$newRecord = $this->createCacheHitRecord();
		$newRecord->setPrimaryKey(CacheController::$recordKey);
		$newRecord->setMissCount(0);
		$newRecord->setHitCount(0);
		$newRecord->save();
		
		$response['status'] = 'success';
		return $response;
	}
	
	/**
	 * Adds the senders ip to the list of subscibers to the GlobalCache
	 * Will also add all currently cached rules to the new subscriber
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	protected abstract function subscribe();
	
	/**
	 * Removes the senders ip from the list of subscibers to the GlobalCache
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	protected abstract function unsubscribe();
	
	/**
	 * Executes the rule given in the 'type' index in the 
	 * @param unknown $variables An array containing the relevant variables for the rule
	 * @return The result of the rule execution in an array
	 * @return The array will also contain a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	public function executeRule($variables){
		$response = array();
		
		if(!isset($variables['type'])){
			$response['status'] = 'failure';
			$response['errmsg'] = 'Attempted to execute a rule without specifying a type.';
		}else{
			switch($variables['type']){
				case('create_rule'):
					$response = $this->createRule($variables);
					break;
					
				case('get_rules'):
					$response = $this->getAllRules();
					break;
					
				case('delete_rule'):
					$response = $this->deleteRule($variables);
					break;
					
				case('subscribe'):
					$response = $this->subscribe();
					break;
					
				case('unsubscribe'):				
					$response = $this->unsubscribe();
					break;
					
				case('clear_locals'):
				case('clear_global'):
				case('clear_all'):
					$response = $this->clearCache($variables['type']);
					break;
					
				default:
					$response['status'] = 'failure';
					$response['errmsg'] = 'Attempted to execute a non supported rule type.';
	 				break;
			}
		}
		
		
		return $response;
	}
	
	protected function getSenderIp(){
		$returned = getenv('HTTP_CLIENT_IP')?:
					getenv('HTTP_X_FORWARDED_FOR')?:
					getenv('HTTP_X_FORWARDED')?:
					getenv('HTTP_FORWARDED_FOR')?:
					getenv('HTTP_FORWARDED')?:
					getenv('REMOTE_ADDR')?:
					null;
		return $returned;
	}
	
	
	
}