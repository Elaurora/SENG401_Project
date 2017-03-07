<?php
/**
 * This class parses incoming requests into a more usable format
 * @author Patrick
 */
class RequestParser {
	/**
	 * Parse the current URL request into a Request object
	 * @return Request
	 * 		The incoming request
	 */
	public function parseRequest() {
		$request = new Request();
		$request->setProtocol("http://");
		
		$requestUrl = $_SERVER['REDIRECT_URL'];
		
		$requestParts = explode('/', trim($requestUrl, '/'));
		
		//part0 will be our app name ('SENG401') part 1 will the API version ('v1')
		$appName = isset($requestParts[0]) ? $requestParts[0] : '';
		$urlRoot = $_SERVER['SERVER_NAME'] . '/' . $appName;	
		$request->setUrlRoot($urlRoot);
		
		$apiVersion =  isset($requestParts[1]) ? $requestParts[1] : '';
		$request->setApiVersion($apiVersion);
		
		//add all the GET variables to the request
		foreach($_GET as $key => $value) {
			$request->addRequestVariable($key, $value);
		}

		//return the build request
		return $request;
		
	}
}