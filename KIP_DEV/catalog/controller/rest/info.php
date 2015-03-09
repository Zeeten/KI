<?php

class ControllerRestinfo extends Controller  { 

    public function info() {
        $json = array('success' => true);
        $headers = apache_request_headers();
        foreach ($headers as $header => $value) {
    	   $head[$header] = $value;
        }    	
        $json['headers'] = $head;
        
        $sid['sessionid'] = $this->session->getId();
        
        foreach ($this->session->data as $key => $value) {
            if(!is_array($this->session->data[$key])){
                $sid[$key] = $value;
            }   
        }
        $j = null;
        foreach ($this->session->data['cart'] as $key => $value) {
            $j[] = $key."".$value;
        }
        
        if($j==null){
        $sid['cart'] = 'Cart is empty';
        }else{
            $sid['cart'] = $j;
        }  
        $sid['sessionid'] = $this->session->getId();

        $json['session'] = $sid;
        $this->response->setOutput(json_encode($json));
    }
    
    function __call( $methodName, $arguments ) {
        call_user_func(array($this, "info"), $arguments);
    }
}
?>