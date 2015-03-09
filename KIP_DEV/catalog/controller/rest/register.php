<?php 
/**
 * register.php
 *
 * Registration management
 *
 * @author     Makai Lajos
 * @copyright  2014
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://webshop.opencart-api.com/schema_v2.0/
 */
class ControllerRestRegister extends Controller {

	public function registerCustomer($data) {

		$this->language->load('checkout/checkout');

		$this->load->model('account/customer');

		$json = array('success' => true);

		// Validate if customer is logged in.
		if ($this->customer->isLogged()) {
			$json['error']		= "User already is logged";	
			$json['success'] = false;
		} 

		// Validate cart has products and has stock.
		/*if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			//$json['redirect'] = $this->url->link('checkout/cart');
			$json['success'] = false;
		}*/

		// Validate minimum quantity requirments.			
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}		

			if ($product['minimum'] > $product_total) {
				//$json['redirect'] = $this->url->link('checkout/cart');
				$json['success'] = false;
				$json['error']['minimum'] = "Product minimum > product total";
				break;
			}				
		}

		if ($json['success']) {					
			if ((utf8_strlen($data['firstname']) < 1) || (utf8_strlen($data['firstname']) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
				$json['success'] = false;
			}

			if ((utf8_strlen($data['lastname']) < 1) || (utf8_strlen($data['lastname']) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
				$json['success'] = false;
			}

			if ((utf8_strlen($data['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $data['email'])) {
				$json['error']['email'] = $this->language->get('error_email');
				$json['success'] = false;
			}

			if ($this->model_account_customer->getTotalCustomersByEmail($data['email'])) {
				$json['error']['warning'] = $this->language->get('error_exists');
				$json['success'] = false;
			}

			if ((utf8_strlen($data['telephone']) < 3) || (utf8_strlen($data['telephone']) > 32)) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
				$json['success'] = false;
			}

			// Customer Group
			$this->load->model('account/customer_group');

			if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $data['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}

			$customer_group = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

			if ($customer_group) {	
				// Company ID
				if ($customer_group['company_id_display'] && $customer_group['company_id_required'] && empty($data['company_id'])) {
					$json['error']['company_id'] = $this->language->get('error_company_id');
					$json['success'] = false;
				}

				// Tax ID
				if ($customer_group['tax_id_display'] && $customer_group['tax_id_required'] && empty($data['tax_id'])) {
					$json['error']['tax_id'] = $this->language->get('error_tax_id');
					$json['success'] = false;
				}						
			}

			// if ((utf8_strlen($data['address_1']) < 3) || (utf8_strlen($data['address_1']) > 128)) {
			// 	$json['error']['address_1'] = $this->language->get('error_address_1');
			// 	$json['success'] = false;
			// }

			// if ((utf8_strlen($data['city']) < 2) || (utf8_strlen($data['city']) > 128)) {
			// 	$json['error']['city'] = $this->language->get('error_city');
			// 	$json['success'] = false;
			// }

			$this->load->model('localisation/country');

			//$country_info = $this->model_localisation_country->getCountry($data['country_id']);

			//if ($country_info) {
				// if ($country_info['postcode_required'] && (utf8_strlen($data['postcode']) < 2) || (utf8_strlen($data['postcode']) > 10)) {
				// 	$json['error']['postcode'] = $this->language->get('error_postcode');
				// 	$json['success'] = false;
				// }

				// VAT Validation
			// 	$this->load->helper('vat');

			// 	if ($this->config->get('config_vat') && $data['tax_id'] && (vat_validation($country_info['iso_code_2'], $data['tax_id']) == 'invalid')) {
			// 		$json['error']['tax_id'] = $this->language->get('error_vat');
			// 		$json['success'] = false;
			// 	}				
			// }

			// if ($data['country_id'] == '') {
			// 	$json['error']['country'] = $this->language->get('error_country');
			// 	$json['success'] = false;
			// }

			// if (!isset($data['zone_id']) || $data['zone_id'] == '') {
			// 	$json['error']['zone'] = $this->language->get('error_zone');
			// 	$json['success'] = false;
			// }

			if ((utf8_strlen($data['password']) < 4) || (utf8_strlen($data['password']) > 20)) {
				$json['error']['password'] = $this->language->get('error_password');
				$json['success'] = false;
			}

			// if ($data['confirm'] != $data['password']) {
			// 	$json['error']['confirm'] = $this->language->get('error_confirm');
			// 	$json['success'] = false;
			// }

			// if ($this->config->get('config_account_id')) {
			// 	$this->load->model('catalog/information');

			// 	$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			// 	if ($information_info && !isset($data['agree'])) {
			// 		$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			// 		$json['success'] = false;
			// 	}
			// }
		}

		if ($json['success']) {
			$this->model_account_customer->addCustomer($data);

			$this->session->data['account'] = 'register';

			if ($customer_group && !$customer_group['approval']) {
				$this->customer->login($data['email'], $data['password']);

				$this->session->data['payment_address_id'] = $this->customer->getAddressId();
				$this->session->data['payment_country_id'] = $data['country_id'];
				$this->session->data['payment_zone_id'] = $data['zone_id'];

				if (!empty($data['shipping_address'])) {
					$this->session->data['shipping_address_id'] = $this->customer->getAddressId();
					$this->session->data['shipping_country_id'] = $data['country_id'];
					$this->session->data['shipping_zone_id'] = $data['zone_id'];
					$this->session->data['shipping_postcode'] = $data['postcode'];					
				}
			} 

			unset($this->session->data['guest']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);	
			unset($this->session->data['payment_methods']);
		}	

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	} 

	/*
	* GUEST FUNCTIONS
	*/	
	public function register() {

		$this->checkPlugin();

		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			//add customer
			$requestjson = file_get_contents('php://input');
		
			$requestjson = json_decode($requestjson, true);           

			if (!empty($requestjson)) {
				$this->registerCustomer($requestjson);
			}else {
				$this->response->setOutput(json_encode(array('success' => false)));
			}
		}else {
				$json["error"]		= "Only POST request method allowed";
				$json["success"]	= false;

				$this->response->setOutput(json_encode($json));
		}    
    }

    public function registerUser() {
		
		$this->checkPlugin();

		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			//add customer
			$data = array(
				"firstname" => $_POST['firstname'],
				"lastname" => $_POST['lastname'],
				"email" => $_POST['email'],
				"telephone" => $_POST['telephone'],
				"password" => $_POST['password'],
			);

			$this->registerCustomer($data);
		}else {
			$json["error"]		= "Only POST request method allowed";
			$json["success"]	= false;

			$this->response->setOutput(json_encode($json));
		}    
    }

    public function editUser() {
		$this->checkPlugin();

		$json = array('success' => true);

		$this->load->model('account/customer');
		$this->language->load('checkout/checkout');

		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			//add customer
			$data = array(
				"id" => $_POST['id'],
				"firstname" => $_POST['firstname'],
				"lastname" => $_POST['lastname'],
				"email" => $_POST['email'],
				"telephone" => $_POST['telephone'],
			);

			if (utf8_strlen($data['id']) < 1) {
				$json['error']['id'] = "Id must be greater than 0!";
				$json['success'] = false;
			}

			if ((utf8_strlen($data['firstname']) < 1) || (utf8_strlen($data['firstname']) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
				$json['success'] = false;
			}

			if ((utf8_strlen($data['lastname']) < 1) || (utf8_strlen($data['lastname']) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
				$json['success'] = false;
			}

			if ((utf8_strlen($data['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $data['email'])) {
				$json['error']['email'] = $this->language->get('error_email');
				$json['success'] = false;
			}

			if ((utf8_strlen($data['telephone']) < 3) || (utf8_strlen($data['telephone']) > 32)) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
				$json['success'] = false;
			}
		

			if ($json['success']) {
 				$customer = $this->model_account_customer->getCustomer($data['id']);
				if($customer!=null){
					$customeremail = $this->model_account_customer->getCustomerByEmail($data['email']);
						if($customeremail != null && (strcmp($customer['customer_id'],$customeremail['customer_id']))==0){
			 				$data['fax'] = $customer['fax'];
			 				$this->model_account_customer->updateCustomer($data);
			 				$json = array('success' => true);
			                $json['message'] = 'Profile is updated';
						}else{
							$json['success'] = false;
							$json['error'] = 'User does not exist';
						}
				}else{
					$json['error'] = 'User does not exist';
				}
			}
		}else {
			$json["error"]		= "Only POST request method allowed";
			$json["success"]	= false;
		}   
	
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		} 
    }

	public function forgetpwd() {
	        $this->load->model('account/customer');
	        $this->language->load('checkout/checkout');
	        $json = array('success' => true);

	        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			
			        $email = $_POST['email'];
			        
			        if ((utf8_strlen($email) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
						$json['error']['email'] = $this->language->get('error_email');
						$json['success'] = false;
					}

					if ($json['success']) {
			        $customer = $this->model_account_customer->getCustomerByEmail($email);

			        if($customer!=null){

			            $this->language->load('account/forgotten');
			            $this->document->setTitle($this->language->get('heading_title'));
			            
			            $this->language->load('mail/forgotten');
			            $password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
			            
			            $this->model_account_customer->editPassword($customer['email'], $password);
			            
			            $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
			            
			            $message  = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
			            $message .= $this->language->get('text_password') . "\n\n";
			            $message .= $password;

			            $mail = new Mail();
			            $mail->protocol = $this->config->get('config_mail_protocol');
			            $mail->parameter = $this->config->get('config_mail_parameter');
			            $mail->hostname = $this->config->get('config_smtp_host');
			            $mail->username = $this->config->get('config_smtp_username');
			            $mail->password = $this->config->get('config_smtp_password');
			            $mail->port = $this->config->get('config_smtp_port');
			            $mail->timeout = $this->config->get('config_smtp_timeout');             
			            $mail->setTo($customer['email']);
			            $mail->setFrom($this->config->get('config_email'));
			            $mail->setSender($this->config->get('config_name'));
			            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			            $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			            $mail->send();

			            $json = array('success' => true);
			            $json['message'] = 'New password has been sent to your email id.';
			        }else{
			            $json['error'] = 'User does not exist.';
			        }
			    }
	    	}else {
				$json["error"]		= "Only POST request method allowed";
				$json["success"]	= false;
			}  

		    if ($this->debug) {
		        echo '<pre>';
		        print_r($json);
		    } else {
		        $this->response->setOutput(json_encode($json));
		    }
	}

	public function changepwd() {
		$this->load->model('account/customer');
		$this->language->load('checkout/checkout');
        $json = array('success' => true);
		
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){

		    if ((utf8_strlen($_POST['id']) < 1) || (utf8_strlen($_POST['id']) > 32)) {
					$json['error']['id'] = 'Id can not be null.';
					$json['success'] = false;
			}
			 if ((utf8_strlen($_POST['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $_POST['email'])) {
					$json['error']['email'] = $this->language->get('error_email');
					$json['success'] = false;
			}
			if ((utf8_strlen($_POST['oldpass']) < 1) || (utf8_strlen($_POST['oldpass']) > 32)) {
					$json['error']['oldpass'] = 'Old Password can not be null.';
					$json['success'] = false;
			}if ((utf8_strlen($_POST['newpass']) < 1) || (utf8_strlen($_POST['newpass']) > 32)) {
					$json['error']['newpass'] = 'New Password can not be null.';
					$json['success'] = false;
			}

	 		if ($json['success']) {
	 			$id = $_POST['id'];
	 			$email = $_POST['email'];
	 			$oldpass  = $_POST['oldpass'];
	 			$newpass  = $_POST['newpass'];
	 			
	 			$customer = $this->model_account_customer->getCustomer($id);

	 			if($customer !=null && $email == $customer['email'] && (sha1($customer['salt'] . sha1($customer['salt'] . sha1($oldpass)))) == $customer['password']){
					$this->model_account_customer->editPassword($email,$newpass);
	 				$json = array('success' => true);
	                $json['message'] = 'Password is changed successfully';
				}else{
	 				$json['error'] = 'Id / email / oldpass is incorrect';
	 			}
	 		}
 		}else{
 			$json["error"]		= "Only POST request method allowed";
			$json["success"]	= false;
 		}      

	 	if ($this->debug) {
		        echo '<pre>';
		        print_r($json);
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
