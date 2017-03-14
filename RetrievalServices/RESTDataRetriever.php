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
		
		$json = file_get_contents($url);
		return $json;
		//to handle receiving images, no longer do json stuff with response
		//return json_decode($json);
		
	}
}
