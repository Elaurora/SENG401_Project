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
	
	        //  If a valid response was received, then...
	
	        if (!empty($response))
	        {
	            //  Assume that the response is a JSON object and
	            //  attempt to encode...
	
	            $jsonString = json_encode($response, JSON_UNESCAPED_UNICODE);
	
	            //  If the response could be encoded, echo. Otherwise,
	            //  assume the response is a JPEG and echo that.
	
	            if ($jsonString === false) {
	            	//if the json string is false, the date was an image
	            	$responseBody = '<body style="margin:0px"><img src="data:$response/jpeg;base64,' . base64_encode($response) . '" style="' . $this->imgStyle . '"></body>';
	            } else {
	            	//if the json object was not false the date was valid json
	            	$responseBody = '<body><pre style="' . $this->preStyle . '">' . $jsonString . '</pre></body>';
	            	
	            }
	                
	        } else {
	           	throw new InvalidArgumentException("Invalid input url, or the request server is down");     
	        }
	
	        
	        return $this->packageResponse($responseBody);
        
        } catch (Exception $e) {
        	return "<div style='font:13px Comic Sans MS'>Yeah, <i>that</i> didn't work. </div>";
        }
    }
    
    /**
     * Adds the HTML wrapper to the response object
     * @param string $responseBody
     * 		An html body tag, with any content
     */
    protected function packageResponse($responseBody) {
    	$prefix = '<html><head></head>';
    	$suffix = '</html>';
    	
    	//  Return the thing.
    	return $prefix . $responseBody . $suffix;
    }
}