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
	 * 
	 * @param man what should the parameters even be. maybe none?
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
				foreach($testJson as $index => $subJson) {
					$resultJson[$index] = $subJson;
				}
				
			} else {
				return $specificResult;
			}
		}
		
		return $resultJson;
	}
	
	
}