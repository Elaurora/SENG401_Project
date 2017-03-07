<?php
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
		
		//part0 will be our app name ('SENG401') part 1 will the API version ('v1'), and part 2 will be the requested module
		$urlRoot = $_SERVER['SERVER_NAME'] . '/';
		
		if(isset($requestParts[0])) {
			$urlRoot .= $requestParts[0];
		}
		
		$request->setUrlRoot($urlRoot);
		$request->setApiVersion(isset($requestParts[1]) ? $requestParts[1] : '');
		
		
		foreach($_GET as $key => $value) {
			$request->addRequestVariable($key, $value);
		}

		return $request;
		
	}
}