<?php

class RESTApiExecutor {

    public function executeFormRequest() {
        $response = (new RESTRequestHandler())->handleRequest();
    }
}