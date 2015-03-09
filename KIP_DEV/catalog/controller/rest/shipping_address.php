<?php 
/**
 * shipping_address.php
 *
 * Shipping address management
 *
 * @author     Makai Lajos
 * @copyright  2014
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://webshop.opencart-api.com/schema_v2.0/
 */
class ControllerRestShippingAddress extends Controller {
	
	/*
	* Get Shipping addresses
	*/
	public function listShippingAddresses() {

		$json = array('success' => true);
		
		$this->language->load('checkout/checkout');
		
					
		if (isset($this->session->data['shipping_address_id'])) {
			$data['address_id'] = $this->session->data['shipping_address_id'];
		} else {
			$data['address_id'] = $this->customer->getAddressId();
		}

		$this->load->model('account/address');

		$data['addresses'] = $this->model_account_address->getAddresses();

		if (isset($this->session->data['shipping_postcode'])) {
			$data['postcode'] = $this->session->data['shipping_postcode'];		
		} else {
			$data['postcode'] = '';
		}
				
		if (isset($this->session->data['shipping_country_id'])) {
			$data['country_id'] = $this->session->data['shipping_country_id'];		
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}
				
		if (isset($this->session->data['shipping_zone_id'])) {
			$data['zone_id'] = $this->session->data['shipping_zone_id'];		
		} else {
			$data['zone_id'] = '';
		}
						
		//$this->load->model('localisation/country');
		
		//$data['countries'] = $this->model_localisation_country->getCountries();

		if(count($data['addresses']) > 0){
			$json["data"] = $data;
		}else {
			$json["success"]	= false;
			$json["error"]		= "No shipping address found";
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
	* Save shipping address to database
	*/
	public function saveShippingAddress($post) {
		$this->language->load('checkout/checkout');
		
		$json = array('success' => true);
		
		// Validate if customer is logged in.
		if (!$this->customer->isLogged()) {
			$json["error"] = "User is not logged in";
			$json["success"] = false;
		}
		
		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json["error"] = "The customer should not have reached this page, becouse shipping is not required.";
			//$json["success"] = false;
		}
		
		// Validate cart has products and has stock.		
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json["error"] = "Validate cart has products and has stock failed";
			$json["success"] = false;
		}	

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
				$json['success'] = false;
				$json['error']['minimum'] = "Product minimum > product total";
				
				break;
			}				
		}
								
		if ($json['success']) {
			if (isset($post['shipping_address']) && $post['shipping_address'] == 'existing') {
				$this->load->model('account/address');
				
				if (empty($post['address_id'])) {
					$json['error']['warning'] = $this->language->get('error_address');
					$json['success'] = false;
				} elseif (!in_array($post['address_id'], array_keys($this->model_account_address->getAddresses()))) {
					$json['error']['warning'] = $this->language->get('error_address');
					$json['success'] = false;
				}
						
				if ($json['success']) {			
					$this->session->data['shipping_address_id'] = $post['address_id'];
					
					// Default Shipping Address
					$this->load->model('account/address');

					$address_info = $this->model_account_address->getAddress($post['address_id']);
					
					if ($address_info) {
						$this->session->data['shipping_country_id'] = $address_info['country_id'];
						$this->session->data['shipping_zone_id'] = $address_info['zone_id'];
						$this->session->data['shipping_postcode'] = $address_info['postcode'];						
					} else {
						unset($this->session->data['shipping_country_id']);	
						unset($this->session->data['shipping_zone_id']);	
						unset($this->session->data['shipping_postcode']);
					}
					
					unset($this->session->data['shipping_method']);							
					unset($this->session->data['shipping_methods']);
				}
			} 
			
			if ($post['shipping_address'] == 'new') {
				if ((utf8_strlen($post['firstname']) < 1) || (utf8_strlen($post['firstname']) > 32)) {
					$json['error']['firstname'] = $this->language->get('error_firstname');
					$json['success'] = false;
				}
		
				if ((utf8_strlen($post['lastname']) < 1) || (utf8_strlen($post['lastname']) > 32)) {
					$json['error']['lastname'] = $this->language->get('error_lastname');
					$json['success'] = false;
				}
		
				if ((utf8_strlen($post['address_1']) < 3) || (utf8_strlen($post['address_1']) > 128)) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
					$json['success'] = false;
				}
		
				if ((utf8_strlen($post['city']) < 2) || (utf8_strlen($post['city']) > 128)) {
					$json['error']['city'] = $this->language->get('error_city');
					$json['success'] = false;
				}
				
				$this->load->model('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($post['country_id']);
				
				if ($country_info && $country_info['postcode_required'] && (utf8_strlen($post['postcode']) < 2) || (utf8_strlen($post['postcode']) > 10)) {
					$json['error']['postcode'] = $this->language->get('error_postcode');
					$json['success'] = false;
				}
				
				if ($post['country_id'] == '') {
					$json['error']['country'] = $this->language->get('error_country');
					$json['success'] = false;
				}
				
				if (!isset($post['zone_id']) || $post['zone_id'] == '') {
					$json['error']['zone'] = $this->language->get('error_zone');
					$json['success'] = false;
				}
				
				if ($json['success']) {						
					// Default Shipping Address
					$this->load->model('account/address');		
					
					$this->session->data['shipping_address_id'] = $this->model_account_address->addAddress($post);
					$this->session->data['shipping_country_id'] = $post['country_id'];
					$this->session->data['shipping_zone_id'] = $post['zone_id'];
					$this->session->data['shipping_postcode'] = $post['postcode'];
									
					unset($this->session->data['shipping_method']);						
					unset($this->session->data['shipping_methods']);
				}
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}

	/*
	* SHIPPING ADDRESS FUNCTIONS
	*/	
	public function shippingaddress() {

		$this->checkPlugin();

		if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
			//get payment addresses
			$this->listShippingAddresses();
		}else if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			//save payment address information to session
			$requestjson = file_get_contents('php://input');
		
			$requestjson = json_decode($requestjson, true);           

			if (!empty($requestjson)) {
				$this->saveShippingAddress($requestjson);
			}else {
				$this->response->setOutput(json_encode(array('success' => false)));
			}
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
