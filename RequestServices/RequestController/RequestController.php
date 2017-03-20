<?php




/**
 * 
 * @author andys
 *
 */
class RequestController {

	
	protected $parser = null;
	protected $builder = null;
	protected $executor = null;
	
	
	public function __construct() {
		$this->parser = new RequestParser();
		$this->builder = new RequestBuilder();
		$this->executor = new RequestExecutor();
	}
	
	/**
	 * Parses, builds and executes the requests, returns a json object or a image stream
	 * @return
	 */
	public function controlThatRequest() {
		
		$myRequest = $this->parser->parseRequest($_SERVER['REQUEST_URI'], $_SERVER['SERVER_NAME'], $_GET);
		$requestGroup = $this->builder->buildRequestGroup($myRequest);
		$result = $this->executor->executeRequest($requestGroup);
		
		$resultJson = array();
		foreach($result as $specificResult) {
			$testJson = json_encode($specificResult);
			
			if($testJson !== false) {
				
				$json = json_decode($specificResult);
				foreach($json as $index => $subJson) {
					if(is_int($index)) {
						$resultJson[] = $subJson;
					} else {
						$resultJson[$index] = $subJson;
					}
					
				}
				
			} else {
				return $specificResult;
			}
		}
		$finalResult['result'] = $resultJson;
		$finalResult['attribution'] = "Powered by Auroras.live";
		
		
		return $finalResult;
	}
	
	
}