<?php

/**
 * Executes a cache configuration command by sending
 * a REST request through the cache API.
 *
 * @authors Patrick and Natalie
 */
class RESTApiExecutor {

    /**
     * Command types.
     *
     * @var string
     */
    private static
        $NEW_CACHE_RULE     = "create_rule",
        $DELETE_CACHE_RULE  = "delete_rule",
        $GET_ALL_RULES      = "get_rules",
        $CLEAR_CACHE        = "clear_all";

    /**
     * IP of global cache server.
     *
     * @var string
     */
    private static $REST_API_ROOT = "http://104.198.102.12";

    /**
     * Sends a cache configuration command via REST request.
     *
     * @param  RequestPath $requestPath
     *      Request to make.
     * @return string
     *      Request result.
     * @throws Exception
     *      On invalid request.
     */
    public function executeFormRequest(RequestPath $requestPath) {
        return file_get_contents($this->buildFormRequest($requestPath));
    }

    /**
     *  Builds a REST request from the RequestPath using the rules outlined
     *  in the API.
     *
     * @param   RequestPath $requestPath
     *      The request object.
     *
     * @return string $restAPIRequest
     *      The REST request URL.
     *
     * @throws Exception
     *      On invalid request.
     */
    private function buildFormRequest(RequestPath $requestPath) {

        //  http://104.198.102.12/?type= ...

        $restAPIRequest = $this::$REST_API_ROOT . "/?type=";

        switch ($requestPath->getCommandType())
        {
            case "newcacherule":

                // ... http://104.198.102.12/?type=create_rule&local_ttl=420&global_ttl=69

                $restAPIRequest .= $this::$NEW_CACHE_RULE;

                if (!isset($_POST["local_ttl"]) || !isset($_POST["global_ttl"]) || !isset($_POST["match_vars"]))
                    throw new Exception("Invalid request: missing parameters");

                $restAPIRequest .= "&local_ttl="        . $_POST["local_ttl"];
                $restAPIRequest .= "&global_ttl="       . $_POST["global_ttl"];
                $restAPIRequest .= "&match_variables="  . $this->parseMatchVars($_POST["match_vars"]);
                break;

            case "deletecacherule":

                // ... http://104.198.102.12/?type=create_rule&rule_id=420

                $restAPIRequest .= $this::$DELETE_CACHE_RULE;

                if (!isset($_POST["rule_id"]))
                    throw new Exception("Invalid request: missing parameters");

                $restAPIRequest .= "&rule_id=" . $_POST["rule_id"];
                break;

            case "getallrules":

                // ... http://104.198.102.12/?type=get_rules

                $restAPIRequest .= $this::$GET_ALL_RULES;
                break;

            case "clearcache":

                // ... http://104.198.102.12/?type=clear_all

                $restAPIRequest .= $this::$CLEAR_CACHE;
                break;

            default:

                // ... party trick

                throw new Exception("Invalid request: config command unknown.");
                break;
        }

        return $restAPIRequest;
    }

    /**
     * Parses the match_vars input into a JSON.
     *
     * @param $matchvars
     *      The string containing the match variables in the form
     *      [name] [value], ..., [name] [value]
     * @return string
     *      The match variables as a JSON string.
     * @throws Exception
     *      On invalid match variables.
     */
    private function parseMatchVars($matchvars) {
        $matchvars = explode(",", $matchvars);
        $json = "{";

        foreach ($matchvars as $index => $pair)
        {
            $json .= $index == 0 ? "" : ",";
            $parts = explode(" ", trim($pair));

            if (count($parts) != 2 && count($parts) != 1)
                throw new Exception("Invalid match variables: did you"
                . " enter them in the form [name] [value], ... , [name] [value]?");

            $json .= "\"" . $parts[0] . "\": " . "\"" . $parts[1] . "\"";
        }

        $json .= "}";
        return $json;
    }
}