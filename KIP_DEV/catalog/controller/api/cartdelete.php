<?php

class ControllerApiCartdelete extends Controller  { 

    public function updatecart() {
        $this->load->model('account/customer');
        $json = array('success' => false);
        
         if (isset($this->request->get['key'])) {
            $key = $this->request->get['key'];

            $json = array('success' => true);
                if (isset($this->session->data['cart'][$key])) {
                    unset($this->session->data['cart'][$key]);
                }
            $this->data = array();
            //$this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
            $json['msg'] = 'Key is deleted successfully.';
        }else{
            $json['error'] = "Please put key in url.";
        }
        if ($this->debug) {
            echo '<pre>';
            print_r($json);
        } else {
            $this->response->setOutput(json_encode($json));
        }
    }
    
    function __call( $methodName, $arguments ) {
        call_user_func(array($this, "updatecart"), $arguments);
    }
}
?>