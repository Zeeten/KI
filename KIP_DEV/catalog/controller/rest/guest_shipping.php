<?php 
/**
 * guest_shipping.php
 *
 * Guest customer shipping management
 *
 * @author     Makai Lajos
 * @copyright  2014
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://webshop.opencart-api.com/schema_v2.0/
 */
class ControllerRestGuestShipping extends Controller {

	/* 
	* Get guest shipping information
	*/
	public function getGuestShipping() {
		
		$json = array('success' => true);

		$this->language->load('checkout/checkout');
		
					
		if (isset($this->session->data['guest']['shipping']['firstname'])) {
			$data['firstname'] = $this->session->data['guest']['shipping']['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->session->data['guest']['shipping']['lastname'])) {
			$data['lastname'] = $this->session->data['guest']['shipping']['lastname'];
		} else {
			$data['lastname'] = '';
		}
		
		if (isset($this->session->data['guest']['shipping']['company'])) {
			$data['company'] = $this->session->data['guest']['shipping']['company'];			
		} else {
			$data['company'] = '';
		}
		
		if (isset($this->session->data['guest']['shipping']['address_1'])) {
			$data['address_1'] = $this->session->data['guest']['shipping']['address_1'];			
		} else {
			$data['address_1'] = '';
		}

		if (isset($this->session->data['guest']['shipping']['address_2'])) {
			$data['address_2'] = $this->session->data['guest']['shipping']['address_2'];			
		} else {
			$data['address_2'] = '';
		}

		if (isset($this->session->data['guest']['shipping']['postcode'])) {
			$data['postcode'] = $this->session->data['guest']['shipping']['postcode'];	
		} elseif (isset($this->session->data['shipping_postcode'])) {
			$data['postcode'] = $this->session->data['shipping_postcode'];								
		} else {
			$data['postcode'] = '';
		}
		
		if (isset($this->session->data['guest']['shipping']['city'])) {
			$data['city'] = $this->session->data['guest']['shipping']['city'];			
		} else {
			$data['city'] = '';
		}

		if (isset($this->session->data['guest']['shipping']['country_id'])) {
			$data['country_id'] = $this->session->data['guest']['shipping']['country_id'];			  	
		} elseif (isset($this->session->data['shipping_country_id'])) {
			$data['country_id'] = $this->session->data['shipping_country_id'];		
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->session->data['guest']['shipping']['zone_id'])) {
			$data['zone_id'] = $this->session->data['guest']['shipping']['zone_id'];	
		} elseif (isset($this->session->data['shipping_zone_id'])) {
			$data['zone_id'] = $this->session->data['shipping_zone_id'];						
		} else {
			$data['zone_id'] = '';
		}
					
		//$this->load->model('localisation/country');
		
		//$data['countries'] = $this->model_localisation_country->getCountries();
		
		
		$json["data"] = $data;

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	
	/* 
	* Save guest shipping address
	*/
	public function saveGuestShipping($data) {
		$this->language->load('checkout/checkout');
		
		$json = array('success' => true);
		
		// Validate if customer is logged in.
		if ($this->customer->isLogged()) {
			$json["error"] = "User is logged in, not guest user";
			$json["success"] = false;
		} 			
		
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json["error"] = "Validate cart has products and has stock failed";
			$json["success"] = false;	
		}
		
		// Check if guest checkout is avaliable.	
		if (!$this->config->get('config_guest_checkout') || $this->config->get('config_customer_price') || $this->cart->hasDownload()) {
			$json["error"] = "Guest checkout is not avaliable";
			$json["success"] = false;
		} 
		
		if ($json['success']) {
			if(isset($data['firstname'])){
				if ((utf8_strlen($data['firstname']) < 1) || (utf8_strlen($data['firstname']) > 32)) {
					$json['error']['firstname'] = $this->language->get('error_firstname');
					$json['success'] = false;
				}
			}else{
				$json['error']['firstname'] = $this->language->get('error_firstname');
				$json['success'] = false;		
			}
			
			if(isset($data['firstname'])){
				if ((utf8_strlen($data['lastname']) < 1) || (utf8_strlen($data['lastname']) > 32)) {
					$json['error']['lastname'] = $this->language->get('error_lastname');
					$json['success'] = false;
				}
			}else{
				$json['error']['lastname'] = $this->language->get('error_lastname');
				$json['success'] = false;		
			}
			
			if(isset($data['address_1'])){
				if ((utf8_strlen($data['address_1']) < 3) || (utf8_strlen($data['address_1']) > 128)) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
					$json['success'] = false;
				}
			}else{
				$json['error']['address_1'] = $this->language->get('error_address_1');
				$json['success'] = false;		
			}

			if(isset($data['city'])){
				if ((utf8_strlen($data['city']) < 2) || (utf8_strlen($data['city']) > 128)) {
					$json['error']['city'] = $this->language->get('error_city');
					$json['success'] = false;
				}
			}else{
				$json['error']['city'] = $this->language->get('error_city');
				$json['success'] = false;		
			}
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($data['country_id']);
			
			if ($country_info && $country_info['postcode_required'] && (utf8_strlen($data['postcode']) < 2) || (utf8_strlen($data['postcode']) > 10)) {
				$json['error']['postcode'] = $this->language->get('error_postcode');
				$json['success'] = false;
			}
	
			if ($data['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
				$json['success'] = false;
			}
			
			if (!isset($data['zone_id']) || $data['zone_id'] == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
				$json['success'] = false;
			}	
		}
		
		if ($json['success']) {
			$this->session->data['guest']['shipping']['firstname'] = trim($data['firstname']);
			$this->session->data['guest']['shipping']['lastname'] = trim($data['lastname']);
			$this->session->data['guest']['shipping']['company'] = trim($data['company']);
			$this->session->data['guest']['shipping']['address_1'] = $data['address_1'];
			$this->session->data['guest']['shipping']['address_2'] = $data['address_2'];
			$this->session->data['guest']['shipping']['postcode'] = $data['postcode'];
			$this->session->data['guest']['shipping']['city'] = $data['city'];
			$this->session->data['guest']['shipping']['country_id'] = $data['country_id'];
			$this->session->data['guest']['shipping']['zone_id'] = $data['zone_id'];
			
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($data['country_id']);
			
			if ($country_info) {
				$this->session->data['guest']['shipping']['country'] = $country_info['name'];	
				$this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];
			} else {
				$this->session->data['guest']['shipping']['country'] = '';	
				$this->session->data['guest']['shipping']['iso_code_2'] = '';
				$this->session->data['guest']['shipping']['iso_code_3'] = '';
				$this->session->data['guest']['shipping']['address_format'] = '';
			}
			
			$this->load->model('localisation/zone');
							
			$zone_info = $this->model_localisation_zone->getZone($data['zone_id']);
		
			if ($zone_info) {
				$this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
				$this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
			} else {
				$this->session->data['guest']['shipping']['zone'] = '';
				$this->session->data['guest']['shipping']['zone_code'] = '';
			}
			
			$this->session->data['shipping_country_id'] = $data['country_id'];
			$this->session->data['shipping_zone_id'] = $data['zone_id'];
			$this->session->data['shipping_postcode'] = $data['postcode'];	
			
			unset($this->session->data['shipping_method']);	
			unset($this->session->data['shipping_methods']);
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
	* GUEST	SHIPPING FUNCTIONS
	*/	
	public function guestshipping() {

		$this->checkPlugin();

		if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
			//get guest shipping
			$this->getGuestShipping();
		}else if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			//save customer shipping
			$requestjson = file_get_contents('php://input');
		
			$requestjson = json_decode($requestjson, true);           

			if (!empty($requestjson)) {
				$this->saveGuestShipping($requestjson);
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
