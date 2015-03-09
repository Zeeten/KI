<?php  

/**
 * login.php
 *
 * Login management
 *
 * @author     Makai Lajos
 * @copyright  2014
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://webshop.opencart-api.com/schema_v2.0/
 */
class ControllerRestSMLogin extends Controller { 
	
	/*
	* Login user
	*/
	public function login() {

		$this->checkPlugin();
		
		$json = array('success' => true);
		
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			$requestjson = file_get_contents('php://input');
		
			$requestjson = json_decode($requestjson, true);

			$post = $requestjson;

			$this->language->load('checkout/checkout');
				
			if ($this->customer->isLogged()) {
				$json['error']		= "User already is logged";			
				$json['success']	= false;			
			}

			/*if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
				$json['error']		= "Something went wrong";			
				$json['success']	= false;			
			}*/	
			if ($json['success']) {
				if (!$this->customer->login($_POST['email'], $_POST['password'])) {
					$json['error']['warning'] = $this->language->get('error_login');
					$json['success']	= false;
				}
			
				$this->load->model('account/customer');
			
				$customer_info = $this->model_account_customer->getCustomerByEmail($_POST['email']);
				
				if ($customer_info && !$customer_info['approved']) {
					$json['error']['warning'] = $this->language->get('error_approved');
					$json['success']	= false;
				}		
			}
			
			if ($json['success']) {
				unset($this->session->data['guest']);
					
				// Default Addresses
				$this->load->model('account/address');
					
				$address_info = $this->model_account_address->getAddress($this->customer->getAddressId());
										
				if ($address_info) {
					if ($this->config->get('config_tax_customer') == 'shipping') {
						$this->session->data['shipping_country_id'] = $address_info['country_id'];
						$this->session->data['shipping_zone_id'] = $address_info['zone_id'];
						$this->session->data['shipping_postcode'] = $address_info['postcode'];	
					}
					
					if ($this->config->get('config_tax_customer') == 'payment') {
						$this->session->data['payment_country_id'] = $address_info['country_id'];
						$this->session->data['payment_zone_id'] = $address_info['zone_id'];
					}
				} else {
					unset($this->session->data['shipping_country_id']);	
					unset($this->session->data['shipping_zone_id']);	
					unset($this->session->data['shipping_postcode']);
					unset($this->session->data['payment_country_id']);	
					unset($this->session->data['payment_zone_id']);	
				}
				

				unset($customer_info['password']);
				$customer_info["session"] = session_id();
				$json['data'] = $customer_info;
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

	/*
	* Book Like
	*/
	public function booklike() {

		$json = array('success' => true);
		$this->language->load('checkout/checkout');
		
		if (utf8_strlen($_GET['id']) < 1) {
				$json['error']['id'] = "Id must be greater than 0!";
				$json['success'] = false;
		}

		if ((utf8_strlen($_GET['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $_GET['email'])) {
			$json['error']['email'] = $this->language->get('error_email');
			$json['success'] = false;
		}


		if ($json['success']) {
			$this->load->model('account/customer');		
			$this->load->model('catalog/product');

			$customer_info = $this->model_account_customer->getCustomerByEmail($_GET['email']);
			$product_info = $this->model_catalog_product->getProduct($_GET['id']);

			if (!$customer_info) {
					$json['error'] = "User does not exist";
					$json['success']	= false;
			}else{
				if($product_info){
					$json['message'] = "Congrats ".$customer_info['firstname'].", You have liked ".$product_info['name'];
        		}else{
        			$json['error'] = "Product does not exist";
					$json['success']	= false;
        		}
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

	/*
	* Book Like
	*/
	public function recentview() {

		$json = array('success' => true);

		$this->load->model('catalog/product');
		$product_info = $this->model_catalog_product->getProduct($_GET['id']);

		if($product_info){
			$json['message'] = $product_info['name']." has viewed recently";
		}else{
			$json['error'] = "Product does not exist";
			$json['success']	= false;
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
	* Book Like
	*/
	public function recentviewslist() {

		$this->checkPlugin();
		
		$json = array('success' => true);
		$json['list'] = array('CREATIVE BOOKS (Hindi)','A SECURITY BOOK - (Hindi)','SELF LEARN HIBERNATE','A SECURITY BOOK - (Bengali)'
			,'A SECURITY BOOK - (Gujrati)','YOU CAN BECOME A HONHAR GUARD - ADVANCE (English)','YOU CAN BECOME A HONHAR GUARD - BASIC (English)'
			,'BAAL-E-UNQA','SELF LEARN ADVANCE JAVA','SHAH-PAR-E-TAAOOS');
		
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


	/*
	Only for testing
	Depracted
	http:/opencart3.my/api/rest/checkuser/key/123
	*/	
	public function checkuser() {

		$json = array('success' => true);	

		if (!$this->customer->isLogged()) {
				$json['error']		= "User is not logged";			
				$json['success']	= false;			
		}else {
			$json['data'] 		= $this->customer->getEmail();			
		}
		
		if ($this->debugIt) {
				echo '<pre>';
				print_r($json);
		} else {
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
