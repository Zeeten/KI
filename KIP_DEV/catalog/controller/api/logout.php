<?php

class ControllerApiLogout extends Controller {

    private $debug = false;
    
    public function logout() {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
        
        unset($this->session->data['customer_id']);

        $this->customer_id = '';
        $this->firstname = '';
        $this->lastname = '';
        $this->email = '';
        $this->telephone = '';
        $this->fax = '';
        $this->newsletter = '';
        $this->customer_group_id = '';
        $this->address_id = '';
        $json = array('success' => true);
        $json['msg'] = 'Loged out successfully.';
        
        if ($this->debug) {
             echo '<pre>';
             print_r($json);
        } else {
            $this->response->setOutput(json_encode($json));
        }
    }

function __call( $methodName, $arguments ) {
    call_user_func(array($this, "logout"), $arguments);
}
}

?>