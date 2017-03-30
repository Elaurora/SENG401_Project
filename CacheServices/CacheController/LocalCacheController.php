<?php


class LocalCacheController extends CacheController{
	
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
	 * Creats a new rule in the cache using the given variables
	 * @param unknown $variables - an array containing the relevant information needed
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	private function createRule($variables){
		
	}
	
	/**
	 * Gets all rules currently in the cache and returns them
	 * @return An array containing the 'rule_id' , 'localttl' , 'globalttl' and 'match_variables' which is an array containing a variable_value for each 'variable_name'
	 * @return The array that is returned will also contain a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	private function getAllRules(){
	
	}
	
	/**
	 * Deletes the rule with the given rule_id from the cache.
	 * @param $rule_id_to_delete the rule_id of the rule that is to be deleted
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	private function deleteRule($rule_id_to_delete){
	
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
					
					break;
				case('get_rules'):
						
					break;
				case('delete_rule'):
						
					break;
				case('subscribe'):
						
					break;
				case('unsubscribe'):
						
					break;
				default:
					$response['status'] = 'failure';
					$response['errmsg'] = 'Attempted to execute a non supported rule type on the LocalCache';
					break;
			}
		}
		
		
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