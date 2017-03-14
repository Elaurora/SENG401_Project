<?php
/**
 * This class parses incoming requests into a more usable format
 * @author Patrick
 */
class RequestParser {
	
	/**
	 * Parse the current URL request into a Request object
	 * @param string $redirectURL
	 * 		The URL extension on the query (probably from $_SERVER['REDIRECT_URL'], unless its being tested
	 * @param string $serverName
	 * 		The IP/domain name of the website (api.auroras.live or localhost or 127.0.0.1 or www.google.ca)
	 * @param string[] $get
	 * 		The get variables, just use $_GET unless you are testing this function
	 * @return Request
	 * 		The incoming request
	 */
	public function parseRequest($redirectURL, $serverName, $get) {
		$request = new Request();
		$request->setProtocol("http://");
		
		$requestUrl = $redirectURL;
		
		$requestParts = explode('/', trim($requestUrl, '/'));
		
		//part0 will be our app name ('SENG401') part 1 will the API version ('v1')
		$appName = isset($requestParts[0]) ? $requestParts[0] : '';
		$urlRoot = $serverName . '/' . $appName;	
		$request->setUrlRoot($urlRoot);
		
		$apiVersion =  isset($requestParts[1]) ? $requestParts[1] : '';
		$request->setApiVersion($apiVersion);
		
		//add all the GET variables to the request
		foreach($get as $key => $value) {
			$request->addRequestVariable($key, $value);
		}

		//return the built request
		return $request;
		
	}
}