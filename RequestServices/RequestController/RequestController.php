<?php




/**
 * 
 * @author andys
 *
 */
class RequestController {

	/**
	 * 
	 * @param man what should the parameters even be. maybe none?
	 * @return
	 */
	public function controlThatRequest($url) {
		$parser = new RequestParser();
		$builder = new RequestBuilder();
		$executor = new RequestExecutor();
		
		$myRequest = $parser->parseRequest($_SERVER['REQUEST_URI'], $_SERVER['SERVER_NAME'], $_GET);
		$requestGroup = $builder->buildRequestGroup($myRequest);
		$result = $executor->executeRequest($requestGroup);
		
		return $result;
	}
	
	
}