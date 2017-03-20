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
	
	protected static $googleUrlRoot = 'maps.googleapis.com';
	
	protected static $googleApi = 'maps/api/staticmap';
	
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
		$returnRequest->setProtocol('https://');
		
		//set the api version to like.... maybe not.
		$requestVars = $request->getRequestVariables();
		if(isset($requestVars['type'])){
			$requestType = $requestVars['type'];
		}
		else {
			throw new Exception("Invalid request: No type specified.");
		}
		
		if ($requestType == 'map'){
			//google stuff
			$returnRequest->setApiVersion($this::$googleApi);
			$returnRequest->setUrlRoot($this::$googleUrlRoot);
			//type=map&id=yellowknife
			if(isset($requestVars['id'])){
				$locationID = $requestVars['id'];
			}
			else {
				throw new Exception("Invalid request: No map location specified.");
			}
			
			//assemble that request i guess? lol
			
			$googleRequestVars = array(
				'center' => $locationID,
				'size' => '600x300',
				'markers' => 'color:red|' . $locationID
			);
		
			$returnRequest->setRequestVariables($googleRequestVars);
			
		}
		else {
			//assume aurora stuff
			$returnRequest->setApiVersion($request->getApiVersion() . '/');
			$returnRequest->setUrlRoot($this::$auroraUrlRoot);
			$requestVariables = $request->getRequestVariables();
			$returnRequest->setRequestVariables($requestVariables);
		}
	
		
			
		$returnGroup = array();
		$returnGroup[] = $returnRequest;
		
		return $returnGroup;
	}
}