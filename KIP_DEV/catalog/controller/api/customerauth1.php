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
            $customer = $this->model_account_customer->getCustomerByEmail($email);

            if($customer != null){

                $epwd = (sha1($customer['salt']. sha1($customer['salt'] . sha1($pwd))));

                if(strcmp($epwd,$customer['password']) == 0){
                    $json = array('success' => true);
                    
                    $json['customer'] = array(
                        'customer_id' => $customer['customer_id'],
                        'store_id' => $customer['store_id'],
                        'firstname' => $customer['firstname'],
                        'lastname' => $customer['lastname'],
                        'email' => $customer['email'],
                        'telephone' => $customer['telephone'],
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
}
if ($this->debug) {
    echo '<pre>';
    print_r($json);
} else {
    $this->response->setOutput(json_encode($json));
}
}

public function _list() {
    $this->load->model('sale/customer');
    $json = array('success' => true, 'customers' => array());

        # -- $_GET params ------------------------------
    if (isset($this->request->get['category'])) {
        $category_id = $this->request->get['category'];
    } else {
        $category_id = 0;
    }
        # -- End $_GET params --------------------------

    $customers = $this->model_sale_customer->getCustomers(array(
            //'filter_name'        => ''
        ));

    foreach ($customers as $customer) {
        $json['customers'][] = array(
            'id' => $customer['customer_id'],
            'firstname' => $customer['firstname'],
            'lastname' => $customer['lastname'],
            'email' => $customer['email'],
            );
    }

    $this->response->setOutput(json_encode($json));
}

function __call( $methodName, $arguments ) {
    call_user_func(array($this, "auth"), $arguments);
}
}

?>