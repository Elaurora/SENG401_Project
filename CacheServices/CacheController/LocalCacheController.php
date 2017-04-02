<?php


class LocalCacheController extends CacheController{
	
	/**
	 * Last resort default Ttl for caches. 60 seconds.
	 * @var integer
	 */
	public static $defaultTtl = 60;
	
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
		$query = CachedRequestQuery::create();
		$query = $query->filterByQueryUrlRoot($request->__toString());
		$query = $query->findOne();
		
		if(!isset($query)){
			// if nothing matches, return false
			$this->incrementCacheMissCounter();
			return false;
		
		}
		
		$id = $query->getQueryId();
		
		// if something does match, get the variables of the matching request
		// check which rule applies to the variables found
		
		$ruleNumbers = array();
		
		foreach ($request->getRequestVariables() as $key => $value){
			//go through each variable, see which rule number applies the most.
			$cacheMatchVariableQuery = CacheMatchVariableQuery::create();
			$cacheMatchVariableQuery = $cacheMatchVariableQuery->filterByVariableName($key);
			$cacheMatchVariableQuery = $cacheMatchVariableQuery->filterByVariableValue($value);
			$cacheMatchVariableQuery = $cacheMatchVariableQuery->findOne();
			if(isset($cacheMatchVariableQuery)){
				$ruleNum = $cacheMatchVariableQuery->getRuleId();
				if (isset($ruleNum)){
					$ruleNumbers[] = $ruleNum;
				}
			}
			
		}
		
		//we found at least one relevant rule #
		if(count($ruleNumbers) > 0){
			$c = array_count_values($ruleNumbers);
			$rule = array_search(max($c), $c);
			
			$cacheRuleQuery = CacheRuleQuery::create();
			$cacheRuleQuery = $cacheRuleQuery->filterByRuleId($rule);
			$cacheRuleQuery = $cacheRuleQuery->findOne();
			$ttl = $cacheRuleQuery->getLocalTtl();
		}
		else{
			// if none, use the default rule
			// FIND A DEFAULT TTL? IS IT THE RULE AT INDEX 0?
			$cacheRuleQuery = CacheRuleQuery::create();
			$cacheRuleQuery = $cacheRuleQuery->filterByRuleId(0);	
			$cacheRuleQuery = $cacheRuleQuery->findOne();
			if(isset($cacheRuleQuery)){
				$ttl = $cacheRuleQuery->getLocalTtl();
			}
			else{
				// if no default rule in table, use another default value in this class
				$ttl = LocalCacheController::$defaultTtl;
			}
		}
		
		
		//check that the query_time+local_ttl is greater than the current time
		//echo($query->getQueryTime() + $ttl);
		//echo("       " + mktime());
		
		if($query->getQueryTime() + $ttl > mktime()){
			//if it is, return the query_response
			$this->incrementCacheHitCounter();
			return $query->getQueryResponse();
		}
		else{
			//if it isn't, delete the cached request, return false
			//First delete all the related variables in get_variables
			$getVars = new \GetVariable();
			$getVars->setQueryId($id);
			$query->getGetVariables()->delete();
			
			$query->delete();
			$this->incrementCacheMissCounter();
			return false;
		}
		
	}
	
	/**
	 * Adds the given request to the cache.
	 * @param unknown $request the request to add to the cache
	 * @param string $response the response of the given request to be cached.
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	public function cacheRequest(Request $request, $response){
		$storedRequest = new \CachedRequest();
		$storedRequest->setQueryUrlRoot($request->__toString());
		$storedRequest->setQueryTime(mktime());
		$storedRequest->setQueryResponse($response);
		
		foreach ($request->getRequestVariables() as $key => $value){	
			$getVars = new \GetVariable();
			$getVars->setVariableName($key);
			$getVars->setVariableValue($value);
			$storedRequest->addGetVariable($getVars);
		}
		
		$storedRequest->save();
	}
	
	/**
	 * Increments the number of misses for the local cache
	 */
	protected function incrementCacheMissCounter() {
		/** Fatal error: Class 'CacheHitRecordQuery' not found
		// Query the one Row in this table. if it doesn't exist, create it.
		$record = CacheHitRecordQuery::create();
		$record = $record->filterByRecordId(CacheController::$recordKey);
		$record = $record->findOne();
		if(!isset($query)){
			// create dat new entry
			$newRecord = new \CacheHitRecord();
			$newRecord->setPrimaryKey(CacheController::$recordKey);
			$newRecord->setMissCount(1);
			$newRecord->setHitCount(0);
			$newRecord->save();
		}
		
		// Otherwise, update the existing row.
		$missCount = $record->getMissCount();
		$missCount += 1;
		$record->setMissCount($missCount);
		$record->save();
		*/
	}
	
	/**
	 * Increments the number of misses for the local cache
	 */
	protected function incrementCacheHitCounter(){
		/** Fatal error: Class 'CacheHitRecordQuery' not found
		// Query the one Row in this table. if it doesn't exist, create it.
		$record = CacheHitRecordQuery::create();
		$record = $record->filterByRecordId(CacheController::$recordKey);
		$record = $record->findOne();
		if(!isset($query)){
			// create dat new entry
			$newRecord = new \CacheHitRecord();
			$newRecord->setPrimaryKey(CacheController::$recordKey);
			$newRecord->setMissCount(0);
			$newRecord->setHitCount(1);
			$newRecord->save();
		}
		
		// Otherwise, update the existing row.
		$hitCount = $record->getHitCount();
		$hitCount += 1;
		$record->setHitCount($hitCount);
		$record->save();
		*/
	}
	
	/**
	 * Creats a new rule in the cache using the given variables
	 * @param unknown $variables - an array containing the indicies: 'localttl' , 'globalttl' , 'match_variables' and 'rule_id'
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	protected function createRule($variables){
		$response = parent::createRule($variables);
		
		if($response !== null) {
			return $response;
		}
		
		//Not implemented
	}
	
	/**
	 * Adds the senders ip to the list of subscibers to the GlobalCache
	 * Will also add all currently cached rules to the new subscriber
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	protected function subscribe(){
		$response = array();
		$response['status'] = 'failure';
		$response['errmsg'] = 'Cannot subscribe to a local cache';
		return $response;
	}
	
	/**
	 * Removes the senders ip from the list of subscibers to the GlobalCache
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	protected function unsubscribe(){
		$response = array();
		$response['status'] = 'failure';
		$response['errmsg'] = 'Cannot unsubscribe to a local cache';
		return $response;
	}
	
	/**
	 * Subscribes this cache to the global cache at the given ip address
	 * @param unknown $globalCache_ip the ip address of the global cache this cache will be subscribing to
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	public function subscribeToGlobalCache($globalCache_ip){
		
	}
	
	/**
	 * unsubscribes this cache from the global cache at the given ip address
	 * @param unknown $globalCache_ip
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	public function unsubscribeFromGlobalCache($globalCache_ip){
		
	}
}