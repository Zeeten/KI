<?php

class ControllerApiCartupdate extends Controller  { 

    public function updatecart() {
        $this->load->model('account/customer');
        $json = array('success' => false);
        
         if (isset($this->request->get['key'])) {
            $key = $this->request->get['key'];
            $qty = $this->request->get['qty'];

            $json = array('success' => true);
                if ((int)$qty && ((int)$qty > 0)) {
                $this->session->data['cart'][$key] = (int)$qty;
                } else {
                    $this->remove($key);
                }
            $this->data = array();

            $this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

            $json['msg'] = 'Quantity is updated successfully.';
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