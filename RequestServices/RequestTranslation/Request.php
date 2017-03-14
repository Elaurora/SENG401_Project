<?php
/**
 * Represents a URL request, either an incoming request or an outgoing request
 * @author Patrick
 *
 */
class Request {	
	
	/**
	 * The TCP level protocol being used (probably either http:// or https://
	 * @var string
	 */
	protected $protocol;
	
	/**
	 * The API version from the request (probably 'v1', as it is the only one as of March 1st, 2017)
	 * @var string
	 */
	protected $apiVersion;
	
	/**
	 * The base URL for the website (ip/SENG401 for us, api.auroras.live for them)
	 * @var string
	 */
	protected $urlRoot;
	
	/**
	 * The GET variable associted with the request, indexed by their variable name
	 * @var string[]
	 */
	protected $requestVariables = array();
	
	/**
	 * Adds a request variable to the working set
	 * @param string $key
	 * @param string $value
	 */
	public function addRequestVariable($key, $value) {
		$this->requestVariables[$key] = $value;
	}
	
	/**
	 * Sets all of the request variables at once
	 * @param string[] $requestVariables
	 */
	public function setRequestVariables($requestVariables) {
		$this->requestVariables = $requestVariables;
	}
	
	/**
	 * Gets the set of request varaibles
	 * @return string[]
	 */
	public function getRequestVariables() {
		return $this->requestVariables;
	}
	
	/**
	 * Sets the URL root of the request
	 * @param string $urlRoot
	 */
	public function setUrlRoot($urlRoot) {
		$this->urlRoot = $urlRoot;
	}
	
	/**
	 * Gets the URL root of the request
	 */
	public function getUrlRoot() {
		return $this->urlRoot;
	}
	
	/**
	 * Sets the API version of the request
	 * @param string $apiVersion
	 */
	public function setApiVersion($apiVersion) {
		$this->apiVersion = $apiVersion;
	}
	
	/**
	 * Gets the API version of the request
	 * @return string
	 */
	public function getApiVersion() {
		return $this->apiVersion;
	}
	
	/**
	 * Sets the protocol of the request ('http://')
	 * @param string $protocol
	 */
	public function setProtocol($protocol) {
		$this->protocol = $protocol;
	}
	
	/**
	 * Gets the protocol of the request ('http://')
	 */
	public function getProtocol() {
		return $this->protocol;
	}	
	
	/**
	 * Generates the full request URL
	 * @return string
	 */
	public function __toString() {
		$url = $this->protocol . $this->urlRoot;
		
		$url = trim($url, '/');
		$apiVersion = trim($this->apiVersion, '/');
		
		$url .= '/' . $apiVersion . '?';
		
		$url .= http_build_query($this->requestVariables);
		
		return $url;
	}
}









