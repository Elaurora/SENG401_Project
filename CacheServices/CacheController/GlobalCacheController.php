<?php

use Propel\Runtime\ActiveQuery\Criteria;
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
		$query = GlobalCachedRequestQuery::create();
		$query = $query->filterByQueryUrlRoot($request->__toString());
		$query = $query->findOne();
		
		if(!isset($query)){
			// if nothing matches, return false
			$this->incrementCacheMissCounter();
			return false;
			
		}
		
		//load all the rules and their variables
		$cacheRuleQuery = GlobalCacheRuleQuery::create()->find();
		
		//the array for saving rules that match
		$matchedRules = array();
		
		//iterate over each rule and see how much it matches
		foreach($cacheRuleQuery as $ruleID => $cacheRule) {
			$overlap = $this->computeVariableSetOverlap($query->getGlobalGetVariables(), $cacheRule->getGlobalCacheMatchVariables());
			
			if($overlap >= 0) {
				$matchedRules[$cacheRule->getRuleId()] = $overlap;
			}
		}
		
		//we found at least one relevant rule #
		if(count($matchedRules) > 0){
			$ruleID = array_search(max($matchedRules), $matchedRules);
			
			$cacheRuleQuery = GlobalCacheRuleQuery::create();
			$cacheRuleQuery = $cacheRuleQuery->filterByRuleId($ruleID);
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
			$response['errmsh'] = 'Attempted to create a rule in a GlobalCache by specifying the rule_id. The global cache will auto-increment rule_ids';
			return $response;
		}
		
		//First see if there is already a rule associated with the given match_variables, so that i replace it if one exists
		$allRules = $this->getAllRules();
		$ruleToEdit = null;
		foreach($allRules['rules'] as $ruleID => $rule){
			if(isset($rule['match_variables']) && isset($variables['match_variables'])){
				foreach($rule['match_variables'] as $matchVars){
					$ruleToEdit = $ruleID;
					foreach($variables['match_variables'] as $newMatchVars){
						if($newMatchVars['variable_name'] == $matchVars['variable_name']
								&& $newMatchVars['variable_value'] == $matchVars['variable_value']){
							$ruleToEdit = null;
							break;
						}
					}
					if($ruleToEdit === null){
						$ruleToEdit = $ruleID;
					}else{
						$ruleToEdit = null;
						break;
					}
				}
				if($ruleToEdit !== null){
					foreach($variables['match_variables'] as $newMatchVars){
						$ruleToEdit = $ruleID;
						foreach($rule['match_variables'] as $matchVars){
							if($newMatchVars['variable_name'] == $matchVars['variable_name']
									&& $newMatchVars['variable_value'] == $matchVars['variable_value']){
										$ruleToEdit = null;
										break;
							}
						}
						if($ruleToEdit === null){
							$ruleToEdit = $ruleID;
						}else{
							$ruleToEdit = null;
							break;
						}
					}
					if($ruleToEdit !== null){
						$ruleToEdit = $ruleID;
						break;
					}
				}
			}
			else if(!isset($rule['match_variables']) && !isset($variables['match_variables'])){
				$ruleToEdit = $ruleID;
				break;
			}
		}
		
		//If i did not find a matching rule, i will need to create a new one as well as new CacheMatchVariables
		if($ruleToEdit === null){
			$newRule = new \GlobalCacheRule();
			$newRule->setLocalTtl($variables['localttl']);
			$newRule->setGlobalTtl($variables['globalttl']);
			
			if(isset($variables['match_variables'])){
				foreach($variables['match_variables'] as $matchVar){
					$newMatchVar = new \GlobalCacheMatchVariable();
					$newMatchVar->setVariableName($matchVar['variable_name']);
					$newMatchVar->setVariableValue($matchVar['variable_value']);
					$newRule->addGlobalCacheMatchVariable($newMatchVar);
				}
			}
			
			$newRule->save();
			$newRuleID = $newRule->getRuleId();
		}
		else{// If i did find a matching rule, i only need to edit the old one
			$ruleQuery = \GlobalCacheRuleQuery::create();
			$ruleQuery->filterByRuleId($ruleToEdit, Criteria::EQUAL);
			$editRule = $ruleQuery->findOne();
			
			$editRule->setLocalTtl($variables['localttl']);
			$editRule->setGlobalTtl($variables['globalttl']);
			
			$editRule->save();
			$newRuleID = $editRule->getRuleId();
		}
		
		//The global cache now needs to inform all of its subscribers about the new rule
		
		$allSubs = \GlobalSubscriberIpQuery::create()->find();
		
		foreach($allSubs as $sub){
			$request = new Request();
			$request->setProtocol('http://');
			$request->setUrlRoot($sub->getSubscriberIp().'/SENG401');
			$request->setApiVersion('v1');
			$request->addRequestVariable('type', 'create_rule');
			$request->addRequestVariable('localttl', $variables['localttl']);
			$request->addRequestVariable('globalttl', $variables['globalttl']);
			
			if(isset($variables['match_variables'])){
				$matchVars = array();
				foreach($variables['match_variables'] as $matchVar){
					$matchVars[$matchVar['variable_name']] = $matchVar['variable_value'];
				}
				$request->addRequestVariable('match_variables', $matchVars);
			}
			
			$request->addRequestVariable('rule_id', $newRuleID);
			$url = $request->__toString();
			$localCacheResponse = file_get_contents($url);
			
			if($localCacheResponse['status'] == 'failure'){

			}
			else if($localCacheResponse['status'] == 'success'){
				
			}else{

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
		
		//got rid of Criteria::EQUAL
		$cacheRuleQuery->filterByRuleId(intval($variables['rule_id']));
		
		$findOneResult = $cacheRuleQuery->findOne();
		
		if($findOneResult === null){
			$response['status'] = 'failure';
			$response['errmsg'] = 'Attempted to delete a rule that did not exist in the database';
			return $response;
		}
		
		$cacheMatchVarsQuery = $this->getCacheMatchVariablesQuery();
		
		//First, delete all match variables with that rule id
		//got rid of Criteria::EQUAL
		$cacheMatchVarsQuery->filterByRuleId(intval($variables['rule_id']));
		$cacheMatchVarsQuery->delete();
		
		$cacheRuleQuery->delete();
		
		// now do the same for all the little kiddies
		// THIS IS NOT TESTED.
		$allSubs = \GlobalSubscriberIpQuery::create()->find();
		
		foreach($allSubs as $sub){
			$request = new Request();
			$request->setProtocol('http://');
			$request->setUrlRoot($sub->getSubscriberIp().'/SENG401');
			$request->setApiVersion('v1');
			$request->addRequestVariable('type', 'delete_rule');
			
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
		
		
		//Implemented but not tested
		
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
	 * Adds the senders ip to the list of subscibers to the GlobalCache
	 * Will also add all currently cached rules to the new subscriber
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	protected function subscribe(){
		$subscriberIP = $this->getSenderIp();
		
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