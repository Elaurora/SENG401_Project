<?php
/**
 * Controls the global flow for a request
 * @author Patrick
 *
 */
class CacheConfigController {
	/**
	 * Handle the current request
	 * @return string
	 * 		The html to display
	 */
	public function handleRequest() {
		$parser = new UrlParser();
		$requestPath = $parser->getRequestPath();
		
		if($requestPath->getRequestType() == RequestPath::FORM_REQUEST) {
			$server = new FormServer();
			
			$form = $server->getForm($requestPath);
			
			return $form;
		} else {
			$submit = new SubmitHandler();
			$submit->submit($requestPath);
		}
	}
}