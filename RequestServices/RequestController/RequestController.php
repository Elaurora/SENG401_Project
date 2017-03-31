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
	protected $cacheController = null;
	
	
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
				header('Content-Type: image/jpeg');
				return $specificResult;
			}
		}
		
		header('Content-Type: application/json');
		
		$finalResult['result'] = $resultJson;
		
		//if the result had a status code, it is probably an arror and should probably be set for our response type
		if(isset($finalResult['result']['statusCode'])) {
			http_response_code($finalResult['result']['statusCode']);
		}
		
		$finalResult['attribution'] = "Powered by Auroras.live";
		
		
		return json_encode($finalResult, JSON_UNESCAPED_UNICODE);
	}
	
	
}