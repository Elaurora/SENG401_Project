<?php
class UrlParser {
	
	/**
	 * Parses the incoming url and returns
	 * @return RequestPath
	 */
	public function getRequestPath() {
		$return = new RequestPath();
		
		$path = $path = $_SERVER['REQUEST_URI'];
		
		//trim the GET query
		$path = explode('?', $path)[0];
		
		//explode the parts of the query
		$parts = explode('/');
		
		//remove the first empty string from the parts
		if(count($parts) > 1) {
			$parts = array_shift($parts);
		}
		
		
		//check if the first part is 'SENG401' and remove it if it is
		if(count($parts) > 1) {
			$first = $parts[0];
			
			if($first == 'SENG401') {
				$parts = array_shift($parts);
				$return->setUrlBase('/SENG401/');
			} else {
				$return->setUrlBase('/');
			}
		}
		
		//get the form name
		if(count($parts) > 1) {
			$first = $parts[0];
			
			$return->setTargetForm($first);
			$parts = array_shift($parts);
		}
		
		//check if its a submit request
		if(count($parts) > 1) {
			$first = $parts[0];
			
			if($first == 'submit') {
				$return->setRequestType(RequestPath::SUBMIT_REQUEST);
			} else {
				$return->setRequestType(RequestPath::FORM_REQUEST);
			}
		}
		
		return $return;
	}
}