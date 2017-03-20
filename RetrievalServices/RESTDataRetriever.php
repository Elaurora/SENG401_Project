<?php
/**
 * @author Andy
 * responsible for retrieving and returning JSON objects
 * sent back from URL requests specified by the RequestExecutor.
 */
class RequestDataRetriever {
	
	/**
	 * Completes the passed URL request and returns
	 * the JSON object retrieved.
	 * @param string $url
	 * 		The request url that was sent to be completed.
	 * @return
	 */
	public function completeRequest($url) {
		//thanks to: http://www.hashbangcode.com/blog/quickest-way-download-web-page-php
		 // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 
		
        // close curl resource to free up system resources 
        curl_close($ch); 
        
		return $output;
	}
}
