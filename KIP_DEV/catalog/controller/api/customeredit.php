<?php
class ControllerApiCustomeredit extends Controller {
	
	private $debug = false;

	public function update() {

		$this->load->model('account/customer');
        $json = array('success' => false);
		

		// if (!$this->customer->isLogged()) {
		// 	$json['error'] = 'Please Login.';
		// }

        $data['id'] = 0;

		if (isset($this->request->get['id'])) {
            $data['id'] = $this->request->get['id'];
        }
		if (isset($this->request->get['firstname'])) {
            $data['firstname'] = $this->request->get['firstname'];
        }
        if (isset($this->request->get['lastname'])) {
            $data['lastname'] = $this->request->get['lastname'];
        }
        if (isset($this->request->get['email'])) {
            $data['email'] = $this->request->get['email'];
        }
        if (isset($this->request->get['telephone'])) {
            $data['telephone'] = $this->request->get['telephone'];
        }
 
 		if($data['id']!=null && $data['id'] > 0){
 			$customer = $this->model_account_customer->getCustomer($data['id']);
 			if($customer!=null){
 				$customeremail = $this->model_account_customer->getCustomerByEmail($data['email']);
	 				if($customeremail != null && $customeremail['customer_id'] == $customer['customer_id']){
		 				$data['fax'] = $customer['fax'];
		 				$this->model_account_customer->updateCustomer($data);
		 				$json = array('success' => true);
		                $json['message'] = 'Customer is updated successfully';
 					}elseif ($customeremail ==null) {
 						$data['fax'] = $customer['fax'];
		 				$this->model_account_customer->updateCustomer($data);
		 				$json = array('success' => true);
		                $json['message'] = 'Customer is updated successfully';	
 					}else{
 						$json['error'] = 'Updated Email is already exist, Please choose different one.';
 					}
 			}else{
 				$json['error'] = 'Id is not exist';
 			}
 		}else{
 			$json['error'] = 'Please give id in url as a parameter';
 		}      

	 	$this->response->setOutput(json_encode($json));        
	}

	function __call( $methodName, $arguments ) {
        call_user_func(array($this, "update"), $arguments);
    }
}
?>