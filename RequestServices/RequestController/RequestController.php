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
		$requestOut = $this->builder->buildRequest($myRequest);
		return $this->executor->executeRequest($requestOut);	
	}
	
	
}