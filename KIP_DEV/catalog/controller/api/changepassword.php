<?php
class ControllerApiChangepassword extends Controller {
	
	private $debug = false;

	public function changepassword() {

		$this->load->model('account/customer');
        $json = array('success' => false);
		

		// if (!$this->customer->isLogged()) {
		// 	$json['error'] = 'Please Login.';
		// }

        $id = 0;

		if (isset($this->request->get['id'])) {
            $id = $this->request->get['id'];
        }
		if (isset($this->request->get['email'])) {
            $email = $this->request->get['email'];
        }
        if (isset($this->request->get['oldpass'])) {
            $oldpass = $this->request->get['oldpass'];
        }
        if (isset($this->request->get['newpass'])) {
            $newpass = $this->request->get['newpass'];
        }
 
 		if($id!=null && $id > 0){
 			$customer = $this->model_account_customer->getCustomer($id);

 			if($customer !=null && $email == $customer['email'] && (sha1($customer['salt'] . sha1($customer['salt'] . sha1($oldpass)))) == $customer['password']){
				$this->model_account_customer->editPassword($email,$newpass);
 				$json = array('success' => true);
                $json['message'] = 'Password is changed successfully';
			}else{
 				$json['error'] = 'Id / email / oldpass is incorrect';
 			}
 		}else{
 			$json['error'] = 'Please give id in url as a parameter';
 		}      

	 	$this->response->setOutput(json_encode($json));        
	}

	function __call( $methodName, $arguments ) {
        call_user_func(array($this, "changepassword"), $arguments);
    }
}
?>