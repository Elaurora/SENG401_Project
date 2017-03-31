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
			$response['errmsh'] = "Attempted to create a rule in a GlobalCache by setting the rule_id, which is not allowed. GlobalCache has autoincrementing rule_id's";
			return $response;
		}
		
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
		
	}
	
	/**
	 * Removes the senders ip from the list of subscibers to the GlobalCache
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	private function unsubscribe(){
		
	}
	
	/**
	 * Executes the rule given in the 'type' index in the
	 * @param unknown $variables An array containing the relevant variables for the rule
	 * @return The result of the rule execution in an array
	 * @return The array will also contain a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	public function executeRule($variables){
		$response = array();
		
		if(!isset($response['type'])){
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
					$response['errmsg'] = 'Attempted to execute a non supported rule type on the GlobalCache';
	 				break;
			}
		}
		
		
		return $response;
	}
	
}