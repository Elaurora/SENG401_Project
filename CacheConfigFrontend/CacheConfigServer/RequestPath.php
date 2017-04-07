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
     * The kind of configuration command to send (newcacherule, for example)
     * null by default, is this is a bad idea...?
     *
     * @var string
     */
	protected $commandType = null;
	
	/**
	 * @return string
	 * 		Gets the path of the request after the domain name
	 */
	public function getFullPath() {
		$base = $this->urlBase;
		$base .= $this->targetForm;
		
		if($this->requestType == $this::SUBMIT_REQUEST) {
			$base .= '/submit';
		}
		
		return $base;
	}
	
	/**
	 * Sets the url base of the request, which is the part of the path before the 'website' starts (usually '/' or '/SENG401/')
	 * @param string $urlBase
	 */
	public function setUrlBase($urlBase) {
	    $this->urlBase = $urlBase;
    }

    /**
     * Gets the url base of the request, which is the part of the path before the 'website' starts (usually '/' or '/SENG401/')
     * @return string
     */
	public function getUrlBase() {
	    return $this->urlBase;
    }

    /**
     * Sets the target form of the request, which is the part of the url after the urlBase
     * @param string $targetForm
     */
	public function setTargetForm($targetForm) {
	    $this->targetForm = $targetForm;
    }
	
    /**
     * Gets the target form of the request, which is the part of the url after the urlBase
     * @return string
     */
	public function getTargetForm() {
	    return $this->targetForm;
    }
	
    /**
     * Sets the request type of the requestm either form or submit, defined above
     * @param int $requestType
     */
	public function setRequestType($requestType) {
	    $this->requestType = $requestType;
    }
	
    /**
     * Gets the request type of the requestm either form or submit, defined above
     * @return int
     */
	public function getRequestType() {
	    return $this->requestType;
    }

    /**
     * Sets the command type.
     *
     * @param $commandType
     */
    public function setCommandType($commandType) {
        $this->commandType = $commandType;
    }

    /**
     * Gets the command type.
     *
     * @return string
     */
    public function getCommandType() {
	    return $this->commandType;
    }
	
}