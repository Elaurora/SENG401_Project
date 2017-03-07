<?php
/**
 * This class takes an incoming request and builds it into the required outgoing requests
 * @author Patrick
 *
 */
class RequestBuilder {
	/**
	 * The URL root of the aurora website
	 * @var string
	 */
	protected static $auroraUrlRoot = 'api.auroras.live';
	
	/**
	 * Builds the URL for the REST request from a request object
	 * 
	 * @param Request $request
	 * 		The request that was sent to this server
	 * @return Request[]
	 * 		The set of requests that must be executed to satisfy $request
	 */
	public function buildRequestGroup(Request $request) {
		//for now we are only interfacing with auroras.live
		
		$returnRequest = new Request();
		$returnRequest->setProtocol('http://');
		$returnRequest->setApiVersion($request->getApiVersion());
		$returnRequest->setUrlRoot($this::$auroraUrlRoot);
		
		$requestVariables = $request->getRequestVariables();
		
		$returnRequest->setReqestVariables($requestVariables);
			
		$returnGroup = array();
		$returnGroup[] = $returnRequest;
		
		return $returnGroup;
	}
}