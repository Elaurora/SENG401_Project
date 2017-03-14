<?php

/**
 *  Receives REST requests and forwards them for
 *  processing and retrieval.
 *
 *  @author     Natalie Wong
 *  @since      March 6, 2017
 *  @version    1.0
 */

class RESTRequestHandler
{
    protected $attribute = "Powered by Auroras.live";

    /**
     *  Forwards REST requests and displays the results,
     *  appending an attribute "Powered by Auroras.live".
     */

    public function handleRequest()
    {
        //  Ask the RequestController to get requested data.

        $requestController = new RequestController();
        $response = $requestController->controlThatRequest();

        //  If a valid response was received, then...

        if (!empty($response))
        {
            //  Assume that the response is a JSON object and
            //  attempt to encode...

            $json = json_encode($response, JSON_UNESCAPED_UNICODE);

            //  If the response could be encoded, echo. Otherwise,
            //  assume the response is a JPEG and echo that.

            if ($json !== false)
            {
                $response = '<pre>' . $json . '</pre>';
            }
            else {
            	$response = '<img src="data:$response/jpeg;base64,' . base64_encode($response) . '" style="-webkit-user-select: none;background-position: 0px 0px, 10px 10px;background-size: 20px 20px;background-color: white;background-image:linear-gradient(45deg, #eee 25%, transparent 25%, transparent 75%, #eee 75%, #eee 100%),linear-gradient(45deg, #eee 25%, transparent 25%, transparent 75%, #eee 75%, #eee 100%);cursor: zoom-out;">';
            }
                
        }
        else
        {
            //  Sassy failing remark using infuriating font for maximum rage.

            echo "<div style='font:13px Comic Sans MS'>Yeah, <i>that</i> didn't work. </div>";
        }

        
        $prefix = '<html><head></head><body style="margin:0px">';
        $suffix = '</body></html>';
        
        //  Return the thing.
        return $prefix . $response . $suffix;
    }
}