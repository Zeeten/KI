<?php

class ControllerApiGetsession extends Controller  { 

    public function getsession() {
        $json = array('success' => true);
   
        $headers = apache_request_headers();

        foreach ($headers as $header => $value) {
			$json["$header"] = $value;    	
    	}
    	     $json['sessionid'] = $this->session->getId();
        $this->response->setOutput(json_encode($json));
    }
    
    function __call( $methodName, $arguments ) {
        call_user_func(array($this, "getsession"), $arguments);
    }
}
?>