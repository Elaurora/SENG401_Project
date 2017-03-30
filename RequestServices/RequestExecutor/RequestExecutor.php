<?php
/**
 * @author Andy
 * Executes the requests passed to it, and
 * returns an assembled JSON object which includes
 * An appropriate message giving credit to Auroras.live.
 * 
 * In the future, this module will also be responsible for
 * making requests to the cache first, as well as sending
 * updated information to the cache
 */
class RequestExecutor {
	
	/**
	 * To be implemented later, perhaps some instance of
	 * a local cacheController to this RequestServices unit.
	 * @var CacheController
	 */
	//protected $cacheFriend;
	
	/**
	 * Executes the passed request and assembles a JSON
	 * object to return.
	 * @param Request[] $request
	 * 		The array of request objects to be executed.
	 */
	public function executeRequest(array $requestGroup) {
		
		$requestDataRetriever = new RequestDataRetriever();
		
		$requestResultObject = array();
		foreach ($requestGroup as $key=>$request){
			
			$variables = $request->getRequestVariables();
			$noCaching = isset($variables['no-caching']) ? $variables['no-caching'] : false;
			if(!$noCaching){
				
				//If the request type is a rule manipulation type, or a subsribe/unsubscribe to the global cache
				if(in_array($variables['type'], CacheController::$ruleTypes)){
					
					//If this is the global database
					if(__GLOBAL_DATABASE__){
						$cacheController = new GlobalCacheController();
						$response = $cacheController->executeRule($variables);
						
					}
					else if(__NODE_SERVER__){
						$cacheController = new LocalCacheController();
						$response = $cacheController->executeRule($variables);
						
					}
					
					
					
				}
				
				
				// Hey cache, have you seen this request? - yo andy/natalie, for this you can use the CacheController Functions getCachedRequest(Request $request)
				// Yes? Thanks!
				// no? I'll ask my friend the data retriever
				
				$requestResult = $requestDataRetriever->completeRequest($request->__toString());
				// Pay the love forward by telling your cache about the hot new tip.
				// $cacheFriend->storeNewEntryEnsemble($request->__toString(), $requestResult);
			}
			else{
				//let's just keep this between u and me. no need to tell the cache ;)
				$requestResult = $requestDataRetriever->completeRequest($request->__toString());
				
			}
			
			
			// Add that hot mess to the current object
			$requestResultObject[$key] = $requestResult;
		}
		
		// That was fun. add our special fun tag to cap things off:
		//$requestResultObject[] = ['Attribution' => 'Powered by Auroras.live'];
		
		// To handle receiving images, no longer encode results as json.
		//return json_encode($requestResultObject);
		return $requestResultObject;
	}
	
}