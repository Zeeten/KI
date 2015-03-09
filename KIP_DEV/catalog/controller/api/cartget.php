<?php

class ControllerApiCartget extends Controller  { 

    public function getcart() {
        $this->load->model('account/customer');
        $json = array('success' => false);
        
         if (isset($this->request->get['email'])) {
            $email = $this->request->get['email'];
            $customer = $this->model_account_customer->getCustomerByEmail($email);
            if($customer!=null){
                $json = array('success' => true);

                $json['customer'] = array(
                    'customer_id' => $customer['customer_id'],
                    'firstname' => $customer['firstname'],
                    'lastname' => $customer['lastname'],
                    'email' => $customer['email'],
                    'cart' => $customer['cart'],
                    'wishlist' => $customer['wishlist'],
                );
            }else{
                $json['error'] = "Email is not exist.";
            }
        }else{
            $json['error'] = "Please put email in url.";
        }
        if ($this->debug) {
            echo '<pre>';
            print_r($json);
        } else {
            $this->response->setOutput(json_encode($json));
        }
    }
    
    function __call( $methodName, $arguments ) {
        call_user_func(array($this, "getcart"), $arguments);
    }
}
?>