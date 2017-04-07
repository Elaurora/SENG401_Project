<?php

class SubmitHandler {

    protected $executor;
    protected $redirect;

    public function __construct() {
        $this->executor = new RESTApiExecutor();
        $this->redirect = new RedirectEngine();
    }

    public function submit($requestPath) {
        $this->executor->executeFormRequest($requestPath);
        $this->redirect->redirectAfterSubmit($requestPath);
    }
}