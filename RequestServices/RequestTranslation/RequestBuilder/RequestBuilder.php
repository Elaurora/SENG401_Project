<?php
class RequestBuilder {
	
	protected static $auroraUrlRoot = 'api.auroras.live';
	
	/**
	 * Builds the URL for the REST request from a request object
	 * 
	 * @param Request $request
	 * 
	 * @return RequestGroup
	 * 		The set of requests that must be executed to satisfy $request
	 */
	public function buildRequestGroup(Request $request) {
		//for now we are only interfacing with auroras.live
		
		$returnRequest = new Request();
		$returnRequest->setProtocol('http://');
		$returnRequest->setApiVersion($request->getApiVersion());
		$returnRequest->setUrlRoot($this::$auroraUrlRoot);
		
		$requestVariables = $request->getRequestVariables();
		
		foreach($requestVariables as $key => $value) {
			$returnRequest->addRequestVariable($key, $value);
		}
		
		$returnGroup = new RequestGroup();
		$returnGroup->addRequest($returnRequest);
		
		return $returnGroup;
	}
}