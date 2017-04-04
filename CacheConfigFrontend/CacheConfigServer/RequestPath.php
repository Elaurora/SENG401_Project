<?php
class RequestPath {
	const FORM_REQUEST = 1;
	const SUBMIT_REQUEST = 2;
	
	/**
	 * One of the constants above
	 * @var int
	 */
	protected $requestType;
	
	
	/**
	 * A string that identifies the target page (i.e. 'manageconfig' or somesuch)
	 * @var string
	 */
	protected $targetForm;
	
	/**
	 * The part of the url between the form name and the domain, either
	 * '/' on deployed server or
	 * '/SENG401/' on dev servers
	 * 
	 * @var string
	 */
	protected $urlBase;
	
	/**
	 * @return string
	 * 		Gets the path of the request after the domain name
	 */
	public function getFullPath() {
		$base = $this->urlBase;
		$base .= $targetForm;
		
		if($requestType == $this::SUBMIT_REQUEST) {
			$base .= '/submit';
		}
		
		return $base;
	}
	
	public function setUrlBase($urlBase);
	
	public function getUrlBase();
	
	public function setTargetForm($targetForm);
	
	public function getTargetForm();
	
	public function setRequestType($requestType);
	
	public function getRequestType();
	
}