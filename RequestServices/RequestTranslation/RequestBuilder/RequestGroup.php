<?php
class RequestGroup {
	protected $requests = array();
	
	public function addRequest(Request $request) {
		$this->requests[] = $request;
	}
	
	public function getRequests() {
		return $this->requests;
	}
}