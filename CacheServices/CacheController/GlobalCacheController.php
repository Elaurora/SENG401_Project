<?php


use Propel\Runtime\ActiveQuery\Criteria;
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