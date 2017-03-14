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
    /**
     *  Forwards REST requests and displays the results,
     *  appending an attribute "Powered by Auroras.live".
     */

    public function handleRequest()
    {
        //  Ask the RequestController to get requested data.

        $requestController = new RequestControllerSTUB();
        $response = $requestController -> getRequest();

        //  If a valid response was received, then...

        if (isset($response) && !empty($response))
        {
            //  Assume that the response is a JSON object and
            //  attempt to encode...

            $json = json_encode($response);

            //  If the response could be encoded, echo. Otherwise,
            //  assume the response is a JPEG and echo that.

            if (!empty($json))
                echo "<div style='font:13px Courier'> $json</div>";
            else
                echo '<img src="data:$response/jpeg;base64,' . base64_encode(file_get_contents($response)) . '">';
        }
        else
        {
            //  Sassy failing remark using infuriating font for maximum rage.

            echo "<div style='font:13px Comic Sans MS'>Yeah, <i>that</i> didn't work. </div>";
        }

        //  Pretending like I know how to do memory management.

        unset($response);
        unset($requestController);
    }
}