<?php


abstract class CacheController{
	
	/**
	 * An array containing all the different rule types that can be executed on a cache
	 * @var array
	 */
	public static $ruleTypes = array (
			'create_rule', 'get_rules', 'delete_rule', 'subscribe', 'unsubscribe'
	);
	
	
	
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
	 * Adds the given request to the cache.
	 * @param unknown $request the request to add to the cache
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	public abstract function cacheRequest(Request $request);
	
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
		
		if(!isset($variables['rule_id'])){
			$response['status'] = 'failure';
			$response['errmsh'] = "Attempted to create a rule in a LocalCache without specifying the rule_id";
			return $response;
		}
		
		return null;
	}
	
	/**
	 * Gets all rules currently in the cache and returns them
	 * @return An array containing the 'rule_id' , 'localttl' , 'globalttl' and 'match_variables' which is an array containing a variable_value for each 'variable_name'
	 * @return 
	 */
	protected abstract function getAllRules(){
		$response = array();
		
		$response['rules'] = array();
		
		if(__NODE_SERVER__){
			$ruleQuery = \CacheRuleQuery::create();
		}else if(__GLOBAL_DATABASE__){
			$ruleQuery = \GlobalCacheRuleQuery::create();
		}
		
		$ruleQueryFindOneResult = $ruleQuery->findOne();
		
		while($ruleQueryFindOneResult !== null){
		
			$rule_id = $ruleQueryFindOneResult->getRuleId();
		
			$response['rules'][$rule_id] = array();
		
			$response['rules'][$rule_id]['local_ttl'] = $ruleQueryFindOneResult->getLocalTtl();
			$response['rules'][$rule_id]['global_ttl'] = $ruleQueryFindOneResult->getGlobalTtl();
		
			//Now get all of the match variables associated with this rule
			if(__NODE_SERVER__){
				$matchVarsQuery = \CacheMatchVariableQuery::create();
			} else if(__GLOBAL_DATABASE__){
				$matchVarsQuery = \GlobalCacheMatchVariableQuery::create();
			}
			
			$matchVarsQuery->filterByRuleId($rule_id, Criteria::EQUAL);
		
			$matchVarsQueryFindOneResult = $matchVarsQuery->findOne();
		
			if($matchVarsQueryFindOneResult !== null)
				$response['rules'][$rule_id]['match_variables'] = array();
					
				while($matchVarsQueryFindOneResult !== null){
		
					$variableName = $matchVarsQueryFindOneResult->getVariableName();
					$variableValue = $matchVarsQueryFindOneResult->getVariableValue();
		
					$newMatchVarsEntry = array();
					$newMatchVarsEntry['variable_name'] = $variableName;
					$newMatchVarsEntry['variable_value'] = $variableValue;
		
					$response['rules'][$rule_id]['match_variables'][] = $newMatchVarsEntry;
		
					$matchVarsQuery->filterByVariableName($variableName, Criteria::NOT_EQUAL);
		
					$matchVarsQueryFindOneResult = $matchVarsQuery->findOne();
				}
					
				$ruleQuery->filterByRuleId($rule_id, Criteria::EQUAL);
					
				$ruleQueryFindOneResult = $ruleQuery->findOne();
		}
		
		$response['status'] = 'success';
		
		return $response;
		
		//Implemented but not tested
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
		
		if(__NODE_SERVER__){
			$cacheRuleQuery = \CacheRuleQuery::create();
		}else if(__GLOBAL_DATABASE__){
			$cacheRuleQuery = \GlobalCacheRuleQuery::create();
		}
		
		$cacheRuleQuery->filterByRuleId($variables['rule_id'], Criteria::EQUAL);
		
		$findOneResult = $cacheRuleQuery->findOne();
		
		if($findOneResult === null){
			$response['status'] = 'failure';
			$response['errmsg'] = 'Attempted to delete a rule that did not exist in the database';
			return $response;
		}
		
		$cacheRuleQuery->delete();
		
		//Now i need to delete all match variables with that rule id
		if(__NODE_SERVER__){
			$cacheMatchVarsQuery = \CacheMatchVariableQuery::create();
		}else if(__GLOBAL_DATABASE__){
			$cacheMatchVarsQuery = \GlobalCacheMatchVariableQuery::create();
		}
		
		$cacheMatchVarsQuery->filterByRuleId($variables['rule_id'], Criteria::EQUAL);
		
		$findOneResult = $cacheMatchVarsQuery->findOne();
		
		while($findOneResult !== null){
			$cacheMatchVarsQuery->delete();
			$cacheMatchVarsQuery->filterByVariableName($findOneResult->getVariableName(), Criteria::NOT_EQUAL);
			$findOneResult = $cacheMatchVarsQuery->findOne();
		}
		
		$response['status'] = 'success';
		
		//Implemented but not tested
		
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
					
				default:
					$response['status'] = 'failure';
					$response['errmsg'] = 'Attempted to execute a non supported rule type.';
	 				break;
			}
		}
		
		
		return $response;
	}
	
	
	
	
}