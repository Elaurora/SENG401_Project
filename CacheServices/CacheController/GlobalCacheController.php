<?php


class GlobalCacheController extends CacheController{
	
	/**
	 * Figures out if the given request exists within the cache, and returns it if does exist and has not expired.
	 * If the request is found in the cache but it is found to be expired, the request will be deleted from the cache and the function
	 * will return a 'status' of 'failure'
	 * @param Request $request - the request to check the cache for
	 * @return An array containing the response for the cached request
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	public function getCachedRequest(Request $request){
		
	}
	
	/**
	 * Adds the given request to the cache.
	 * @param unknown $request the request to add to the cache
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	public function cacheRequest(Request $request){
		
	}
	
	/**
	 * Creats a new rule in the cache using the given variables
	 * @param unknown $variables - an array containing the indicies: 'localttl' , 'globalttl' , 'match_variables'
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	private function createRule($variables){
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
		
		if(isset($variables['rule_id'])){
			$response['status'] = 'failure';
			$response['errmsg'] = "Attempted to create a rule in a GlobalCache by setting the rule_id, which is not allowed. GlobalCache has autoincrementing rule_id's";
			return $response;
		}
		
		//This is a list of all the r
		$MatchVarRuleIDs = array();
		
		//First see if there is already a rule associated with the given match_variables, so that i replace it if one exists
		foreach($variables['match_variables'] as $name => $value){
			$CMVQ = GlobalCacheMatchVariableQuery::create();
			$CMVQ = $CMVQ->filterByVariableName($name);
			$CMVQ = $CMVQ->filterByVariableValue($value);
			
			$MatchVarRuleIDs[] = $CMVQ;
		}
		
		
			
		$newRule = new \GlobalCacheRule();
		$newRule->setLocalTtl($variables['localttl']);
		$newRule->setGlobalTtl($variables['globalttl']);
	
		
		
		//Not implemented 
	}
	
	/**
	 * Gets all rules currently in the cache and returns them
	 * @return An array containing the 'rule_id' , 'localttl' , 'globalttl' and 'match_variables' which is an array containing a variable_value for each 'variable_name'
	 * @return The array that is returned will also contain a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	private function getAllRules(){
		
	}
	
	/**
	 * Deletes the rule with the given rule_id from the cache. It will also delete the rule from all subscribed caches
	 * @param $variables an array containing the index 'rule_id' which indicates which rule is to be deleted
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	private function deleteRule($variables){
		$response = array();
		
		if(!isset($variables['rule_id'])){
			$response['status'] = 'failure';
			$response['errmsg'] = 'Attempted to delete a rule without specifying a rule_id';
			return $response;
		}
		
		//Not implemented
	}
	
	/**
	 * Adds the senders ip to the list of subscibers to the GlobalCache
	 * Will also add all currently cached rules to the new subscriber
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	private function subscribe(){
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
	private function unsubscribe(){
		$response = array();
		
		$unsubscriberIP = 0;
		
		$SubQuery = GlobalSubscriberIpQuery::create();
		$SubQuery = $SubQuery->filterBySubscriberIp($unsubscriberIP);
		
		$ToBeDeleted = $SubQuery->findOne();
		
		$ToBeDeleted->delete();
		
		$response['status'] = 'success';
	}
	
	
	
	
}