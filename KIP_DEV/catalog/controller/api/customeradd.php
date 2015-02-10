<?php

class ControllerApiCustomeradd extends Controller {

    private $debug = false;


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

    public function register() {
        $this->load->model('account/customer');
        $json = array('success' => false);



        if (isset($this->request->get['firstname'])) {
            $data['firstname'] = $this->request->get['firstname'];
        }
        if (isset($this->request->get['lastname'])) {
            $data['lastname'] = $this->request->get['lastname'];
        }
        if (isset($this->request->get['email'])) {
            $data['email'] = $this->request->get['email'];
            $email = $this->request->get['email'];
        }
        if (isset($this->request->get['password'])) {
            $data['password'] = $this->request->get['password'];
        }
      
        $customer = $this->model_account_customer->getCustomerByEmail($email);

        if($customer == null){
            $json = array('success' => true);
            $data['telephone'] = '';
            $data['fax'] = '';
            $data['company'] = '';
            $data['company_id'] = '';
            $data['tax_id'] = '';
            $data['address_1'] = '';
            $data['address_2'] = '';
            $data['city'] = '';
            $data['postcode'] = '';
            $data['country_id'] = '';
            $data['zone_id'] = '';
            # -- End $_GET params --------------------------

            if ($data) {
                $this->model_account_customer->addCustomer($data);
                $json['message'] = 'Customer is added successfully';
            }

        }else{
             $json = array('success' => false);
             $json['error'] = 'Email is already exist';
        }

        $this->response->setOutput(json_encode($json));
    }

    function __call( $methodName, $arguments ) {
        call_user_func(array($this, "register"), $arguments);
    }
}

?>