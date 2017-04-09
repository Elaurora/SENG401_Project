<?php

/**
 * Handles the submission of a cache configuration
 * command.
 *
 * @authors Patrick and Natalie
 */
class SubmitHandler {

    /**
     * The RESTApiExecutor to execute the submitted
     * request.
     *
     * @var RESTApiExecutor
     */
    protected $executor;

    /**
     * Issues a redirect after submission.
     *
     * @var RedirectEngine
     */
    protected $redirect;

    /**
     * SubmitHandler constructor.
     */
    public function __construct() {
        $this->executor = new RESTApiExecutor();
        $this->redirect = new RedirectEngine();
    }

    /**
     * Submits a cache configuration command.
     *
     * @param RequestPath $requestPath
     *      The submitted command.
     */
    public function submit(RequestPath $requestPath) {
        $this->executor->executeFormRequest($requestPath);
        $this->redirect->redirectAfterSubmit($requestPath);
    }
}