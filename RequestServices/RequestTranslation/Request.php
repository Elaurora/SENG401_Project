<?php
/**
 * Represents a URL request, either an incoming request or an outgoing request
 * @author Patrick
 *
 */
class Request {	
	protected $protocol;
	
	protected $apiVersion;
	
	protected $urlRoot;
	
	protected $requestVariables = array();
	
	public function addRequestVariable($key, $value) {
		$this->requestVariables[$key] = $value;
	}
	
	public function getRequestVariables() {
		return $this->requestVariables;
	}
	
	public function setUrlRoot($urlRoot) {
		$this->urlRoot = $urlRoot;
	}
	
	public function getUrlRoot() {
		return $this->urlRoot;
	}
	
	public function setApiVersion($apiVersion) {
		$this->apiVersion = $apiVersion;
	}
	
	public function getApiVersion() {
		return $this->apiVersion;
	}
	
	public function setProtocol($protocol) {
		$this->protocol = $protocol;
	}
	
	public function getProtocol() {
		return $this->protocol;
	}	
	
	/**
	 * Generates the full request URL
	 */
	public function __toString() {
		$url = $this->protocol . $this->urlRoot;
		
		$url = trim($url, '/');
		$apiVersion = trim($this->apiVersion, '/');
		
		$url .= '/' . $apiVersion . '/?';
		
		$url .= http_build_query($this->requestVariables);
		
		return $url;
	}
}









