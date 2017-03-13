<?php

/**
 * Receives REST requests and forwards them for
 * processing and retrieval.
 *
 * @author Natalie
 */
class RestRequestHandler
{
    protected $requestController;

    //  l o l

    public function handleRequest()
    {
        $requestController = new RequestController();
        $response = $requestController -> getRequest();

        if (isset($response) && !empty($response))
        {
            $json = json_encode($response, JSON_PRETTY_PRINT);

            if (!empty($json))
                echo $json;
            else
                echo '<img src="data:$response/jpeg;base64,' . base64_encode(file_get_contents($response)) . '">';
        }
        else
        {
            echo "Yeah, <i>that</i> didn't work.";
        }

        unset($response);
        unset($requestController);
    }
}