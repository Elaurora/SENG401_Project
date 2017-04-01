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
	 * The data retriever for executing remote queries
	 * @var RequestDataRetriever $requestDataRetriever
	 */
	protected $requestDataRetriever;
	
	/**
	 * The local cache controller
	 * @var CacheController $localCacheController
	 */
	protected $localCacheController;
	
	/**
	 * The global cache controller
	 * @var CacheController $localCacheController
	 */
	protected $globalCacheController;
	
	public function __construct() {
		$this->requestDataRetriever = new RequestDataRetriever();
		$this->localCacheController = new LocalCacheController();
		$this->globalCacheController = new GlobalCacheController();
	}
	
	/**
	 * To be implemented later, perhaps some instance of
	 * a local cacheController to this RequestServices unit.
	 * @var CacheController
	 */
	//protected $cacheFriend;
	
	/**
	 * Executes the passed request and assembles a JSON
	 * object to return.
	 * @param Request $request
	 * 		The request object to be executed.
	 * @return string
	 * 		The output string for the service
	 */
	public function executeRequest($request) {
		
		

		$variables = $request->getRequestVariables();
		$noCaching = isset($variables['no-caching']) ? $variables['no-caching'] : false;
		
		
		if(isset($variables['type']) && in_array($variables['type'], CacheController::$ruleTypes)) {
			//If this is the global database
			if(__GLOBAL_DATABASE__){			
				$requestResultJson = $this->globalCacheController->executeRule($variables);		
				$requestResult = json_encode($requestResultJson, JSON_UNESCAPED_UNICODE);
			}
			else if(__NODE_SERVER__){			
				$requestResultJson = $this->localCacheController->executeRule($variables);
				$requestResult = json_encode($requestResultJson, JSON_UNESCAPED_UNICODE);
			}
		} else if(!$noCaching){
			
		
			// Hey cache, have you seen this request? - yo andy/natalie, for this you can use the CacheController Functions getCachedRequest(Request $request)
			// Yes? Thanks!
			// no? I'll ask my friend the data retriever
			$requestResult = $this->requestDataRetriever->completeRequest($request->__toString());
			// Pay the love forward by telling your cache about the hot new tip.
			// $cacheFriend->storeNewEntryEnsemble($request->__toString(), $requestResult);
		}
		else{
			//let's just keep this between u and me. no need to tell the cache ;)
			$requestResult = $this->requestDataRetriever->completeRequest($request->__toString());
			
		}
		
		
		
		
		return $this->buildResult($requestResult, $request);
	}
	
	/**
	 * Processes the return executing the request into the thing that should be echo'd
	 * @param unknown $rawResult
	 * @param Request $triggeringRequest
	 * 		The request that caused this result, may affect the retrn value
	 */
	protected function buildResult($rawResult, $triggeringRequest) {
		
		
		$testJson = json_encode($rawResult);
		
		if($testJson === false) {
			header('Content-Type: image/jpeg');
			return $rawResult;
		}
		
		$resultJson = json_decode($rawResult);
		
		header('Content-Type: application/json');
	
		if($triggeringRequest->getUrlRoot() == RequestBuilder::$auroraUrlRoot) {
			
		
		
			$finalResult['result'] = $resultJson;
		
			//if the result had a status code, it is probably an arror and should probably be set for our response type
			if(is_array($finalResult['result']) && isset($finalResult['result']['statusCode'])) {
				http_response_code($finalResult['result']['statusCode']);
			}
			
			if(is_object($finalResult['result']) && isset($finalResult['result']->statusCode)) {
				http_response_code($finalResult['result']->statusCode);
			}
		
			$finalResult['attribution'] = "Powered by Auroras.live";
	
	
		} else {
			$finalResult = $resultJson;
		}
		
		return json_encode($finalResult, JSON_UNESCAPED_UNICODE);
		
	}
	
}