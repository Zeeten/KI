<?php

class ControllerApiCustomerauth extends Controller {

    private $debug = false;
    
    public function auth() {
        $this->load->model('account/customer');
        $json = array('success' => false);

        $customer = null;

        if (isset($this->request->get['email']) && isset($this->request->get['pwd'])) {
            $email = $this->request->get['email'];
            $pwd = $this->request->get['pwd'];
            
            $customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($pwd) . "'))))) OR password = '" . $this->db->escape(md5($pwd)) . "') AND status = '1' AND approved = '1'");


            if ($customer_query->num_rows) {
                $this->session->data['customer_id'] = $customer_query->row['customer_id'];  
                
                if ($customer_query->row['cart'] && is_string($customer_query->row['cart'])) {
                    $cart = unserialize($customer_query->row['cart']);
                    
                    foreach ($cart as $key => $value) {
                        if (!array_key_exists($key, $this->session->data['cart'])) {
                            $this->session->data['cart'][$key] = $value;
                        } else {
                            $this->session->data['cart'][$key] += $value;
                        }
                    }           
                }

                if ($customer_query->row['wishlist'] && is_string($customer_query->row['wishlist'])) {
                    if (!isset($this->session->data['wishlist'])) {
                        $this->session->data['wishlist'] = array();
                    }

                    $wishlist = unserialize($customer_query->row['wishlist']);

                    foreach ($wishlist as $product_id) {
                        if (!in_array($product_id, $this->session->data['wishlist'])) {
                            $this->session->data['wishlist'][] = $product_id;
                        }
                    }           
                }

                $this->customer_id = $customer_query->row['customer_id'];
                $this->firstname = $customer_query->row['firstname'];
                $this->lastname = $customer_query->row['lastname'];
                $this->email = $customer_query->row['email'];
                $this->telephone = $customer_query->row['telephone'];
                $this->fax = $customer_query->row['fax'];
                $this->newsletter = $customer_query->row['newsletter'];
                $this->customer_group_id = $customer_query->row['customer_group_id'];
                $this->address_id = $customer_query->row['address_id'];

                $this->db->query("UPDATE " . DB_PREFIX . "customer SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

                $json = array('success' => true);
                $customer = $this->model_account_customer->getCustomerByEmail($email);
                $json['customer'] = array(
                    'customer_id' => $customer['customer_id'],
                    'store_id' => $customer['store_id'],
                    'firstname' => $customer['firstname'],
                    'lastname' => $customer['lastname'],
                    'email' => $customer['email'],
                    'telephone' => $customer['telephone'],
                    'session_id' => $this->session->getId(),
                    'fax' => $customer['fax'],
                    'salt' => $customer['salt'],
                    'cart' => $customer['cart'],
                    'wishlist' => $customer['wishlist'],
                    'newsletter' => $customer['newsletter'],
                    'address_id' => $customer['address_id'],
                    'customer_group_id' => $customer['customer_group_id'],
                    'ip' => $customer['ip'],
                    'status' => $customer['status'],
                    'approved' => $customer['approved'],
                    'token' => $customer['token'],
                    'date_added' => $customer['date_added'],
                    );
}
}
if ($this->debug) {
    echo '<pre>';
    print_r($json);
} else {
    $this->response->setOutput(json_encode($json));
}
}

function __call( $methodName, $arguments ) {
    call_user_func(array($this, "auth"), $arguments);
}
}

?>