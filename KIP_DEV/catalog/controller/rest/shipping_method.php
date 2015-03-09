<?php 
/**
 * shipping_method.php
 *
 * Shipping method management
 *
 * @author     Makai Lajos
 * @copyright  2014
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://webshop.opencart-api.com/schema_v2.0/
 */
class ControllerRestShippingMethod extends Controller {
	
	/*
	* Get shipping methods
	*/
  	public function listShippingMethods() {

		$json = array('success' => true);

		$this->language->load('checkout/checkout');

		$this->load->model('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {					
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		
		
		} elseif (isset($this->session->data['guest']) && isset($this->session->data['guest']['shipping'])) {
			$shipping_address = $this->session->data['guest']['shipping'];
		}

		if (!empty($shipping_address)) {
			// Shipping Methods
			$quote_data = array();

			$this->load->model('setting/extension');

			$results = $this->model_setting_extension->getExtensions('shipping');

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('shipping/' . $result['code']);

					$quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address); 

					if ($quote) {
						$quote_data[$result['code']] = array( 
							'title'      => $quote['title'],
							'quote'      => $quote['quote'], 
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}

			$sort_order = array();

			foreach ($quote_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $quote_data);

			$this->session->data['shipping_methods'] = $quote_data;
		}

		if (empty($this->session->data['shipping_methods'])) {
			$data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
			$json["success"] = false;
		} else {
			$data['error_warning'] = '';
		}	

		if (isset($this->session->data['shipping_methods'])) {
			$data['shipping_methods'] = $this->session->data['shipping_methods']; 
		} else {
			$data['shipping_methods'] = array();
		}

		if (isset($this->session->data['shipping_method']['code'])) {
			$data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->session->data['comment'])) {
			$data['comment'] = $this->session->data['comment'];
		} else {
			$data['comment'] = '';
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
	* Save selected shipping method to session
	*/
	public function saveShippingMethod($post) {
		
		$this->language->load('checkout/checkout');

		$json = array('success' => true);		

		// Validate if shipping is required. If not the customer should not have reached this page.
		
		if (!$this->cart->hasShipping()) {
			$json["error"] = "The customer should not have reached this page, becouse shipping is not required.";
			$json["success"] = true;	
		}

		// Validate if shipping address has been set.		
		$this->load->model('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {					
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		
		} elseif (isset($this->session->data['guest'])) {
			$shipping_address = $this->session->data['guest']['shipping'];
		}

		if (empty($shipping_address)) {								
			$json["error"] = "Empty shipping address";
			$json["success"] = false;
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
			if (!isset($post['shipping_method'])) {
				$json['error']['warning'] = $this->language->get('error_shipping');
				$json['success'] = false;
				
			} else {
				$shipping = explode('.', $post['shipping_method']);

				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
					$json['error']['warning'] = $this->language->get('error_shipping');
					$json['success'] = false;
				}
			}

			if ($json['success']) {
				$shipping = explode('.', $post['shipping_method']);

				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

				$this->session->data['comment'] = strip_tags($post['comment']);
			}							
		}

		$this->response->setOutput(json_encode($json));	
	}

	/*
	* SHIPPING METHOD FUNCTIONS
	*/	
	public function shippingmethods() {

		$this->checkPlugin();

		if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
			//get shipping methods
			$this->listShippingMethods();
		}else if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			//save shipping information to session
			$requestjson = file_get_contents('php://input');
		
			$requestjson = json_decode($requestjson, true);           

			if (!empty($requestjson)) {
				$this->saveShippingMethod($requestjson);
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
