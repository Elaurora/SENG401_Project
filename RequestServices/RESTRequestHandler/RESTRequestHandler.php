<?php

/**
 *  Receives REST requests and forwards them for
 *  processing and retrieval.
 *
 *  @author     Natalie Wong
 *  @since      March 6, 2017
 *  @version    1.0
 */

class RESTRequestHandler { 
    protected $imgStyle = 
    	"-webkit-user-select: none;
    	background-position: 
    	0px 0px, 10px 10px;
    	background-size: 20px 20px;
    	background-color: white;
    	background-image:linear-gradient(45deg, #eee 25%, transparent 25%, transparent 75%, #eee 75%, #eee 100%),linear-gradient(45deg, #eee 25%, transparent 25%, transparent 75%, #eee 75%, #eee 100%);
    	cursor: zoom-out;";
    
    protected $preStyle = "word-wrap: break-word; white-space: pre-wrap;";

    /**
     * Executes the request and returns html to print
     * @return string
     */
    public function handleRequest() {
        //  Ask the RequestController to get requested data.

    	try {	
	        $requestController = new RequestController();
	        $response = $requestController->controlThatRequest();
	
	        if (empty($response)) {
	           	throw new InvalidArgumentException("Invalid input url, or the request server is down");     
	        }
	        
        } catch (Exception $e) {
        	$response = json_encode(array("error" => "{$e->getMessage()}"));
        }
        
        return $response;
    }
    
    
}