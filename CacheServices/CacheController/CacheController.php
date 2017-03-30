<?php



abstract class CacheController{
	
	/**
	 * An array containing all the different rule types that can be executed on a cache
	 * @var array
	 */
	public static final $ruleTypes = array (
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
	 * @param unknown $variables - an array containing the relevant information needed
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	private abstract function createRule($variables);
	
	/**
	 * Gets all rules currently in the cache and returns them
	 * @return An array containing the 'rule_id' , 'localttl' , 'globalttl' and 'match_variables' which is an array containing a variable_value for each 'variable_name'
	 * @return 
	 */
	private abstract function getAllRules();
	
	/**
	 * Deletes the rule with the given rule_id from the cache. If this is the global cache, it will also delete the rule from all subscribed caches
	 * @param $rule_id_to_delete the rule_id of the rule that is to be deleted
	 * @return An array with a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	private abstract function deleteRule($rule_id_to_delete);
	
	/**
	 * Executes the rule given in the 'type' index in the 
	 * @param unknown $variables An array containing the relevant variables for the rule
	 * @return The result of the rule execution in an array
	 * @return The array will also contain a 'status' index of either 'success' or 'failure'. In the case of failure, the 'errmes' index will have more information
	 */
	public abstract function executeRule($variables);
	
	
	
	
}