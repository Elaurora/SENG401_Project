<?php

class GlobalCacheController extends CacheController{
	
	/**
	 * Last resort default Ttl for caches. 120 seconds.
	 * @var integer
	 */
	public static $defaultTtl = 120;
	
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
		$query = GlobalCachedRequestQuery::create();
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
			$cacheMatchVariableQuery = GlobalCacheMatchVariableQuery::create();
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
			
			$cacheRuleQuery = GlobalCacheRuleQuery::create();
			$cacheRuleQuery = $cacheRuleQuery->filterByRuleId($rule);
			$cacheRuleQuery = $cacheRuleQuery->findOne();
			$ttl = $cacheRuleQuery->getLocalTtl();
		}
		else{
			// if none, use the default rule
			// FIND A DEFAULT TTL? IS IT THE RULE AT INDEX 0?
			$cacheRuleQuery = GlobalCacheRuleQuery::create();
			$cacheRuleQuery = $cacheRuleQuery->filterByRuleId(0);
			$cacheRuleQuery = $cacheRuleQuery->findOne();
			if(isset($cacheRuleQuery)){
				$ttl = $cacheRuleQuery->getLocalTtl();
			}
			else{
				// if no default rule in table, use another default value in this class
				$ttl = GlobalCacheController::$defaultTtl;
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
			$getVars = new \GlobalGetVariable();
			$getVars->setQueryId($id);
			$query->getGlobalGetVariables()->delete();
			
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
		$storedRequest = new \GlobalCachedRequest();
		$storedRequest->setQueryUrlRoot($request->__toString());
		$storedRequest->setQueryTime(mktime());
		$storedRequest->setQueryResponse($response);
		
		foreach ($request->getRequestVariables() as $key => $value){
			$getVars = new \GlobalGetVariable();
			$getVars->setVariableName($key);
			$getVars->setVariableValue($value);
			$storedRequest->addGlobalGetVariable($getVars);
		}
		
		$storedRequest->save();
	}
	
	/**
	 * Increments the number of misses for the global cache
	 */
	protected function incrementCacheMissCounter(){
		
		// Query the one Row in this table. if it doesn't exist, create it.
		$record = GlobalCacheHitRecordQuery::create();
		$record = $record->filterByRecordId(CacheController::$recordKey);
		$record = $record->findOne();
		if(!isset($query)){
			// create dat new entry
			$newRecord = new \GlobalCacheHitRecord();
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
	}
	
	/**
	 * Increments the number of misses for the global cache
	 */
	protected function incrementCacheHitCounter(){
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
		//First see if there is already a rule associated with the given match_variables, so that i replace it if one exists
		$allRules = $this::getAllRules();
		$ruleToDelete = null;
		foreach($allRules['rules'] as $ruleID => $rule){
			foreach($rule['match_variables'] as $matchVars){
				$ruleToDelete = $ruleID;
				foreach($variables['match_variables'] as $newMatchVars){
					if($newMatchVars['variable_name'] == $matchVars['variable_name']
							&& $newMatchVars['variable_value'] == $matchVars['variable_value']){
						$ruleToDelete = null;
						break;
					}
				}
				if($ruleToDelete === null){
					$ruleToDelete = $ruleID;
				}else{
					$ruleToDelete = null;
					break;
				}
			}
			if($ruleToDelete !== null){
				foreach($variables['match_variables'] as $newMatchVars){
					$ruleToDelete = $ruleID;
					foreach($rule['match_variables'] as $matchVars){
						if($newMatchVars['variable_name'] == $matchVars['variable_name']
								&& $newMatchVars['variable_value'] == $matchVars['variable_value']){
									$ruleToDelete = null;
									break;
						}
					}
					if($ruleToDelete === null){
						$ruleToDelete = $ruleID;
					}else{
						$ruleToDelete = null;
						break;
					}
				}
				if($ruleToDelete !== null){
					$ruleToDelete = array();
					$ruleToDelete['rule_id'] = $ruleID;
					//TODO:EDIT THIS RULE INSTEAD OF DELETE
					break;
				}
			}
		}
		
		//Now, if a rule with the same match_variables as the one being created already existed, it has been deleted.
		
		$newRule = new \GlobalCacheRule();
		$newRule->setLocalTtl($variables['localttl']);
		$newRule->setGlobalTtl($variables['globalttl']);
		$newRule->save();
		
		//The global cache now needs to inform all of its subscribers about the new rule
		
		
		
		//Not implemented 
	}
	
	/**
	 * Adds the senders ip to the list of subscibers to the GlobalCache
	 * Will also add all currently cached rules to the new subscriber
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	protected function subscribe(){
		$subscriberIP = 0;
		
		//Initialize and save the new subscriber into the database
		$subscriber = new \GlobalSubscriberIp();
		$subscriber->setSubscriberIp($subscriberIP);
		$subscriber->save();
		
		//Not implemented
		
		//need to send all current cache rules to the new subscriber
	}
	
	/**
	 * Removes the senders ip from the list of subscibers to the GlobalCache
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	protected function unsubscribe(){
		$response = array();
		
		$unsubscriberIP = 0;
		
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