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
	 * Adds the given request to the cache.
	 * @param unknown $request the request to add to the cache
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	public function cacheRequest(Request $request){
		
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
	 * Gets all rules currently in the cache and returns them
	 * @return An array containing the 'rule_id' , 'localttl' , 'globalttl' and 'match_variables' which is an array containing a variable_value for each 'variable_name'
	 * @return The array that is returned will also contain a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	protected function getAllRules(){
	
	}
	
	/**
	 * Deletes the rule with the given rule_id from the cache.
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