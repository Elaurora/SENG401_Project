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
		$parts = explode('/', $path);

		//remove the first empty string from the parts
		if(count($parts) > 0) {
			array_shift($parts);
		}

		//check if the first part is 'SENG401' and remove it if it is
		if(count($parts) > 0) {
			$first = $parts[0];
			
			if($first == 'SENG401') {
				array_shift($parts);
				$return->setUrlBase('/SENG401/');
			} else {
				$return->setUrlBase('/');
			}
		}

		//get the form name

		if(count($parts) > 0) {
			$first = $parts[0];

			$return->setTargetForm($first);
			array_shift($parts);
		}

		//check if its a submit request
		if(count($parts) > 0) {
			$first = $parts[0];

			if($first == 'submit') {
				$return->setRequestType(RequestPath::SUBMIT_REQUEST);

				//  Encore!! [applause emoji x 3]

				array_shift($parts);

				//  One last thing to see what kind of config command it is...

				if (count($parts) > 0) {

				    //  Not checking to see what it is haha yay.

                    $return->setCommandType($parts[0]);
                }

			} else {
				$return->setRequestType(RequestPath::FORM_REQUEST);
			}
		} else {
			$return->setRequestType(RequestPath::FORM_REQUEST);
		}
		
		return $return;
	}
}