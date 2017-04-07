<?php

class RESTApiExecutor {

    //  Tags for the command sets.

    private static $NEW_CACHE_RULE    = "create_rule";
    private static $DELETE_CACHE_RULE = "delete_rule";
    private static $GET_ALL_RULES     = "get_rules";
    private static $CLEAR_CACHE       = "clear_cache";

    // Super special hardcoded IP.

    private static $REST_API_IP     = "104.198.102.12";

    /**
     *  Sends a cache config command via REST request.
     *
     *  @param  RequestPath $requestPath
     */
    public function executeFormRequest(RequestPath $requestPath) {
        return file_get_contents($this->buildFormRequest($requestPath));
    }

    /**
     *  Builds a REST request from the RequestPath using the rules outlined
     *  in the API.
     *
     * @param   RequestPath $requestPath
     *      The request object
     *
     * @return string $restAPIRequest
     *      The REST request url
     *
     * @throws Exception
     *      Invalid request.
     */
    private function buildFormRequest(RequestPath $requestPath) {

        //  104.198.102.12/?type= ...

        $restAPIRequest = $this::$REST_API_IP . "/?type=";

        switch ($requestPath->getCommandType())
        {
            case "newcacherule":

                // ... 104.198.102.12/?type=create_rule&local_ttl=420&global_ttl=69

                $restAPIRequest .= $this::$NEW_CACHE_RULE;

                if (!isset($_POST["local_ttl"]) || !isset($_POST["global_ttl"]))
                    throw new Exception("Invalid request: missing parameters");

                $restAPIRequest .= "&local_ttl="   . $_POST["local_ttl"];
                $restAPIRequest .= "&global_ttl="  . $_POST["global_ttl"];

                break;

            case "deletecacherule":

                // ... 104.198.102.12/?type=create_rule&rule_id=420

                $restAPIRequest .= $this::$DELETE_CACHE_RULE;

                if (!isset($_POST["rule_id"]))
                    throw new Exception("Invalid request: missing parameters");

                $restAPIRequest .= "&rule_id="    . $_POST["rule_id"];

                break;

            case "getallrules":

                // ... 104.198.102.12/?type=get_rules

                $restAPIRequest .= $this::$GET_ALL_RULES;
                break;

            case "clearcache":

                // ... 104.198.102.12/?type=clear_cache

                $restAPIRequest .= $this::$CLEAR_CACHE;
                break;

            default:

                // ... party trick

                throw new Exception("Invalid request: config command unknown.");
                break;
        }

        return $restAPIRequest;
    }
}