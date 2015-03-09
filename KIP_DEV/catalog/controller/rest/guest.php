<?php 
/**
 * guest.php
 *
 * Guest customer management
 *
 * @author     Makai Lajos
 * @copyright  2014
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://webshop.opencart-api.com/schema_v2.0/
 */
class ControllerRestGuest extends Controller {
  	
	/*
	* Get guest user from session
	*/
	public function getGuest() {

		$json = array('success' => true); 
		
		if (isset($this->session->data['guest']['firstname'])) {
			$data['firstname'] = $this->session->data['guest']['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->session->data['guest']['lastname'])) {
			$data['lastname'] = $this->session->data['guest']['lastname'];
		} else {
			$data['lastname'] = '';
		}
		
		if (isset($this->session->data['guest']['email'])) {
			$data['email'] = $this->session->data['guest']['email'];
		} else {
			$data['email'] = '';
		}
		
		if (isset($this->session->data['guest']['telephone'])) {
			$data['telephone'] = $this->session->data['guest']['telephone'];		
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->session->data['guest']['fax'])) {
			$data['fax'] = $this->session->data['guest']['fax'];				
		} else {
			$data['fax'] = '';
		}

		if (isset($this->session->data['guest']['payment']['company'])) {
			$data['company'] = $this->session->data['guest']['payment']['company'];			
		} else {
			$data['company'] = '';
		}

		$this->load->model('account/customer_group');

		$data['customer_groups'] = array();
		
		if (is_array($this->config->get('config_customer_group_display'))) {
			$customer_groups = $this->model_account_customer_group->getCustomerGroups();
			
			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$data['customer_groups'][] = $customer_group;
				}
			}
		}
		
		if (isset($this->session->data['guest']['customer_group_id'])) {
    		$data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
		} else {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}
		
		// Company ID
		if (isset($this->session->data['guest']['payment']['company_id'])) {
			$data['company_id'] = $this->session->data['guest']['payment']['company_id'];			
		} else {
			$data['company_id'] = '';
		}
		
		// Tax ID
		if (isset($this->session->data['guest']['payment']['tax_id'])) {
			$data['tax_id'] = $this->session->data['guest']['payment']['tax_id'];			
		} else {
			$data['tax_id'] = '';
		}
								
		if (isset($this->session->data['guest']['payment']['address_1'])) {
			$data['address_1'] = $this->session->data['guest']['payment']['address_1'];			
		} else {
			$data['address_1'] = '';
		}

		if (isset($this->session->data['guest']['payment']['address_2'])) {
			$data['address_2'] = $this->session->data['guest']['payment']['address_2'];			
		} else {
			$data['address_2'] = '';
		}

		if (isset($this->session->data['guest']['payment']['postcode'])) {
			$data['postcode'] = $this->session->data['guest']['payment']['postcode'];							
		} elseif (isset($this->session->data['shipping_postcode'])) {
			$data['postcode'] = $this->session->data['shipping_postcode'];			
		} else {
			$data['postcode'] = '';
		}
		
		if (isset($this->session->data['guest']['payment']['city'])) {
			$data['city'] = $this->session->data['guest']['payment']['city'];			
		} else {
			$data['city'] = '';
		}

		if (isset($this->session->data['guest']['payment']['country_id'])) {
			$data['country_id'] = $this->session->data['guest']['payment']['country_id'];			  	
		} elseif (isset($this->session->data['shipping_country_id'])) {
			$data['country_id'] = $this->session->data['shipping_country_id'];		
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->session->data['guest']['payment']['zone_id'])) {
			$data['zone_id'] = $this->session->data['guest']['payment']['zone_id'];	
		} elseif (isset($this->session->data['shipping_zone_id'])) {
			$data['zone_id'] = $this->session->data['shipping_zone_id'];						
		} else {
			$data['zone_id'] = '';
		}
					
		$data['shipping_required'] = $this->cart->hasShipping();
		
		if (isset($this->session->data['guest']['shipping_address'])) {
			$data['shipping_address'] = $this->session->data['guest']['shipping_address'];			
		} else {
			$data['shipping_address'] = true;
		}					
		
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
	* Save guest data to session
	*/
	public function addGuest($data) {
    	
		$this->language->load('checkout/checkout');

		$json = array('success' => true);
		
		// Validate if customer is logged in.
		if ($this->customer->isLogged()) {
			$json['error'] = "Customer is logged in.";
			$json['success'] = false;
		} 			
		
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['error'] = "Validate cart has products and has stock failed.";	
			$json['success'] = false;
		}
		
		// Check if guest checkout is avaliable.			
		if (!$this->config->get('config_guest_checkout') || $this->config->get('config_customer_price') || $this->cart->hasDownload()) {
			$json['error']= "Guest checkout is not avaliable";
			$json['success'] = false;
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
						
			if ((utf8_strlen($data['address_1']) < 3) || (utf8_strlen($data['address_1']) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
				$json['success'] = false;
			}
	
			if ((utf8_strlen($data['city']) < 2) || (utf8_strlen($data['city']) > 128)) {
				$json['error']['city'] = $this->language->get('error_city');
				$json['success'] = false;
			}
			
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($data['country_id']);
			
			if ($country_info) {
				if ($country_info['postcode_required'] && (utf8_strlen($data['postcode']) < 2) || (utf8_strlen($data['postcode']) > 10)) {
					$json['error']['postcode'] = $this->language->get('error_postcode');
					$json['success'] = false;
				}
				
				// VAT Validation
				$this->load->helper('vat');
				
				if ($this->config->get('config_vat') && $data['tax_id'] && (vat_validation($country_info['iso_code_2'], $data['tax_id']) == 'invalid')) {
					$json['error']['tax_id'] = $this->language->get('error_vat');
					$json['success'] = false;
				}					
			}
	
			if ($data['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
				$json['success'] = false;
			}
			
			if ($data['zone_id'] == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
				$json['success'] = false;
			}	
		}
			
		if ($json['success']) {
			$this->session->data['guest']['customer_group_id'] = $customer_group_id;
			$this->session->data['guest']['firstname'] = $data['firstname'];
			$this->session->data['guest']['lastname'] = $data['lastname'];
			$this->session->data['guest']['email'] = $data['email'];
			$this->session->data['guest']['telephone'] = $data['telephone'];
			$this->session->data['guest']['fax'] = $data['fax'];
			
			$this->session->data['guest']['payment']['firstname'] = $data['firstname'];
			$this->session->data['guest']['payment']['lastname'] = $data['lastname'];				
			$this->session->data['guest']['payment']['company'] = $data['company'];
			$this->session->data['guest']['payment']['company_id'] = $data['company_id'];
			$this->session->data['guest']['payment']['tax_id'] = $data['tax_id'];
			$this->session->data['guest']['payment']['address_1'] = $data['address_1'];
			$this->session->data['guest']['payment']['address_2'] = $data['address_2'];
			$this->session->data['guest']['payment']['postcode'] = $data['postcode'];
			$this->session->data['guest']['payment']['city'] = $data['city'];
			$this->session->data['guest']['payment']['country_id'] = $data['country_id'];
			$this->session->data['guest']['payment']['zone_id'] = $data['zone_id'];
							
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($data['country_id']);
			
			if ($country_info) {
				$this->session->data['guest']['payment']['country'] = $country_info['name'];	
				$this->session->data['guest']['payment']['iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['guest']['payment']['iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['guest']['payment']['address_format'] = $country_info['address_format'];
			} else {
				$this->session->data['guest']['payment']['country'] = '';	
				$this->session->data['guest']['payment']['iso_code_2'] = '';
				$this->session->data['guest']['payment']['iso_code_3'] = '';
				$this->session->data['guest']['payment']['address_format'] = '';
			}
						
			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($data['zone_id']);
			
			if ($zone_info) {
				$this->session->data['guest']['payment']['zone'] = $zone_info['name'];
				$this->session->data['guest']['payment']['zone_code'] = $zone_info['code'];
			} else {
				$this->session->data['guest']['payment']['zone'] = '';
				$this->session->data['guest']['payment']['zone_code'] = '';
			}
			
			if (!empty($data['shipping_address'])) {
				$this->session->data['guest']['shipping_address'] = true;
			} else {
				$this->session->data['guest']['shipping_address'] = false;
			}
			
			// Default Payment Address
			$this->session->data['payment_country_id'] = $data['country_id'];
			$this->session->data['payment_zone_id'] = $data['zone_id'];
			
			if ($this->session->data['guest']['shipping_address']) {
				$this->session->data['guest']['shipping']['firstname'] = $data['firstname'];
				$this->session->data['guest']['shipping']['lastname'] = $data['lastname'];
				$this->session->data['guest']['shipping']['company'] = $data['company'];
				$this->session->data['guest']['shipping']['address_1'] = $data['address_1'];
				$this->session->data['guest']['shipping']['address_2'] = $data['address_2'];
				$this->session->data['guest']['shipping']['postcode'] = $data['postcode'];
				$this->session->data['guest']['shipping']['city'] = $data['city'];
				$this->session->data['guest']['shipping']['country_id'] = $data['country_id'];
				$this->session->data['guest']['shipping']['zone_id'] = $data['zone_id'];
				
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
	
				if ($zone_info) {
					$this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
					$this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
				} else {
					$this->session->data['guest']['shipping']['zone'] = '';
					$this->session->data['guest']['shipping']['zone_code'] = '';
				}
				
				// Default Shipping Address
				$this->session->data['shipping_country_id'] = $data['country_id'];
				$this->session->data['shipping_zone_id'] = $data['zone_id'];
				$this->session->data['shipping_postcode'] = $data['postcode'];
			}
			
			$this->session->data['account'] = 'guest';
			
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
	* Get zone list
	*/
  	public function zone() {
		
		$this->checkPlugin();
		
		$json = array('success' => true);
		
		if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){

			$this->load->model('localisation/zone');
			if (isset($this->request->get['country_id']) && ctype_digit($this->request->get['country_id'])) {			
				$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

				$zones = array();
		
				foreach ($results as $result) {
					$zones[] = array(
							'zone_id'	=> $result['zone_id'],
							'country_id'=> $result['country_id'],
							'name'		=> $result['name'],
							'status'	=> $result['status'],
							'code'		=> $result['code']
					);
				}

				if(count($zones) == 0){
					$json['success'] 	= false;
					$json['error'] 		= "No result";
				}else {
					$json['data'] 	= $zones;	
				}				

			}else {
				$json["error"]		= "Missing or wrong country_id parameter";
				$json["success"]	= false;
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
	* GUEST FUNCTIONS
	*/	
	public function guest() {

		$this->checkPlugin();

		if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
			//get guest
			$this->getGuest();
		}else if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			//add guest
			$requestjson = file_get_contents('php://input');
		
			$requestjson = json_decode($requestjson, true);           

			if (!empty($requestjson)) {
				$this->addGuest($requestjson);
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
