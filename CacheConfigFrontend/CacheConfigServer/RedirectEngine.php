<?php

/**
 * Executes url redirects
 * @author Patrick
 *
 */
class RedirectEngine {
	
	/**
	 * 
	 * @param RequestPath $requestPath
	 */
	public function redirectAfterSubmit($requestPath) {
		$requestPath = clone $requestPath;
		$requestPath->setRequestType(RequestPath::FORM_REQUEST);
		
		$uri = $requestPath->getFullPath();
		
		header('Location: ' . $uri);
	}
}