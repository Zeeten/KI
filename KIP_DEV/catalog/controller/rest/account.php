<?php
class ControllerRestAccount extends Controller {

	private $error = array();

	public function getAccount() {

		$json = array('success' => true);

		$this->language->load('account/edit');


		if (!$this->customer->isLogged()) {
			$json["error"] = "User is not logged in";
			$json["success"] = false;
		}
		
		if($json["success"]){
		
			$this->load->model('account/customer');
			
			$data['action'] = $this->url->link('account/edit', '', 'SSL');

			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
			unset($customer_info["password"]);
			unset($customer_info["salt"]);
			unset($customer_info["cart"]);
			$json["data"] = $customer_info;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}

	public function saveAccount($post) {

		$json = array('success' => true);
		
		if (!$this->customer->isLogged()) {
			$json["error"] = "User is not logged in";
			$json["success"] = false;
		}else {
			if ($this->validate($post)) {
				$this->load->model('account/customer');
				$this->model_account_customer->editCustomer($post);			
			}else {
				$json["error"] = $this->error;
				$json["success"] = false;
			}
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}					
	}

	protected function validate($post) {
		
		$this->load->model('account/customer');
		$this->language->load('account/edit');

		if(isset($post['firstname'])){
			if ((utf8_strlen($post['firstname']) < 1) || (utf8_strlen($post['firstname']) > 32)) {
				$this->error['firstname'] = $this->language->get('error_firstname');
			}
		}else{
				$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if(isset($post['lastname'])){		
			if ((utf8_strlen($post['lastname']) < 1) || (utf8_strlen($post['lastname']) > 32)) {
				$this->error['lastname'] = $this->language->get('error_lastname');
			}
		}else{
				$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if(isset($post['email'])){
			if ((utf8_strlen($post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $post['email'])) {
				$this->error['email'] = $this->language->get('error_email');
			}
		}else{
				$this->error['email'] = $this->language->get('error_email');
		}
		
		if(isset($post['email'])){
			if (($this->customer->getEmail() != $post['email']) && $this->model_account_customer->getTotalCustomersByEmail($post['email'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}else{
				$this->error['email'] = "E-mail is required";
		}
		
		if(isset($post['telephone'])){
		
			if ((utf8_strlen($post['telephone']) < 3) || (utf8_strlen($post['telephone']) > 32)) {
				$this->error['telephone'] = $this->language->get('error_telephone');
			}
		}else{
				$this->error['telephone'] = $this->language->get('error_telephone');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	/*
	* ACCOUNT FUNCTIONS
	*/	
	public function account() {

		$this->checkPlugin();
		
		if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
			//get account details
			$this->getAccount();			
		} else if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			//modify account
			$requestjson = file_get_contents('php://input');
		
			$requestjson = json_decode($requestjson, true);           

			if (!empty($requestjson)) {       
				$this->saveAccount($requestjson);
			}else {
				$this->response->setOutput(json_encode(array('success' => false)));
			}   

		} 
    	}
	
	public function password() {

		$this->checkPlugin();
		
		 if ( $_SERVER['REQUEST_METHOD'] === 'PUT' ){
			//modify account password
			$requestjson = file_get_contents('php://input');
		
			$requestjson = json_decode($requestjson, true);           

			if (!empty($requestjson)) {       
				$this->changePassword($requestjson);
			}else {
				$this->response->setOutput(json_encode(array('success' => false)));
			}   

		}

    	}

	public function changePassword($post) {

		$json = array('success' => true);
		
		if (!$this->customer->isLogged()) {
			$json["error"] = "User is not logged in";
			$json["success"] = false;
		}else {
			if ((utf8_strlen($post['password']) < 4) || (utf8_strlen($post['password']) > 20)) {
				$json["error"]['password'] = $this->language->get('error_password');
			}

			if ($post['confirm'] != $post['password']) {
				$json["error"]['confirm'] = $this->language->get('error_confirm');
			}

			if (empty($json["error"])) {
				$this->load->model('account/customer');

				$this->model_account_customer->editPassword($this->customer->getEmail(), $post['password']);			
			}else {
				$json["success"] = false;
			}
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}					
	}

	private function checkPlugin() {
		$this->config->set('config_error_display', 0);

		$this->response->addHeader('Content-Type: application/json');
		
		$json = array("success"=>false);

		/*check rest api is enabled*/
		if (!$this->config->get('rest_api_status')) {
			$json["error"] = 'API is disabled. Enable it!';
		}
	

		$headers = apache_request_headers();
		
		$key = "";

		if(isset($headers['X-Oc-Merchant-Id'])){
			$key = $headers['X-Oc-Merchant-Id'];
		}else if(isset($headers['X-OC-MERCHANT-ID'])) {
			$key = $headers['X-OC-MERCHANT-ID'];
		}
			
		/*validate api security key*/
		if ($this->config->get('rest_api_key') && ($key != $this->config->get('rest_api_key'))) {
			$json["error"] = 'Invalid secret key';
		}

		if(isset($json["error"])){			
			echo(json_encode($json));
			exit;
		}else {
			$this->response->setOutput(json_encode($json));			
		}	
	}
}

if( !function_exists('apache_request_headers') ) {
    function apache_request_headers() {
        $arh = array();
        $rx_http = '/\AHTTP_/';

        foreach($_SERVER as $key => $val) {
            if( preg_match($rx_http, $key) ) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();
           // do some nasty string manipulations to restore the original letter case
           // this should work in most cases
                $rx_matches = explode('_', $arh_key);

                if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
                    foreach($rx_matches as $ak_key => $ak_val) {
                        $rx_matches[$ak_key] = ucfirst($ak_val);
                    }

                    $arh_key = implode('-', $rx_matches);
                }

                $arh[$arh_key] = $val;
            }
        }
        
        return( $arh );
    }
}
?>
