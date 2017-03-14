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
        $response = $requestController->getRequest();

        //  If a valid response was received, then...

        if (!empty($response))
        {
            //  Assume that the response is a JSON object and
            //  attempt to encode...

            $json = json_encode($response);

            //  If the response could be encoded, echo. Otherwise,
            //  assume the response is a JPEG and echo that.

            if (isset($json))
                echo "<div style='font:13px Courier'>$json</div>";
            else
                echo '<img src="data:$response/jpeg;base64,' . base64_encode($response) . '">';
        }
        else
        {
            //  Sassy failing remark using infuriating font for maximum rage.

            echo "<div style='font:13px Comic Sans MS'>Yeah, <i>that</i> didn't work. </div>";
        }

        echo "<div style=' font:13px Courier'> $this->attribute </div>";

        //  Pretending like I know how to do memory management.

        unset($response, $requestController);
    }
}