<?php 
/**
 * order.php
 *
 * Orders management
 *
 * @author     Makai Lajos
 * @copyright  2014
 * @license    License.txt
 * @version    2.0
 * @link       http://opencart-api.com/product/opencart-restful-api-pro-v2-0/
 * @see        http://webshop.opencart-api.com/schema_v2.0/
 */
class ControllerRestOrder extends Controller {
	
	private $error = array();

	public function listOrders() {

		$json = array('success' => true);
		
		if (!$this->customer->isLogged()) {
			$json["error"] = "User is not logged in";
			$json["success"] = false;
		}

		$this->language->load('account/order');

		$this->load->model('account/order');

		if($json["success"]){
			$page = 1;

			$data['orders'] = array();

			$order_total = $this->model_account_order->getTotalOrders();

			$results = $this->model_account_order->getOrders(($page - 1) * 10, 1000);

			foreach ($results as $result) {
				$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
				$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

				$data['orders'][] = array(
					'order_id'   => $result['order_id'],
					'name'       => $result['firstname'] . ' ' . $result['lastname'],
					'status'     => $result['status'],
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'products'   => ($product_total + $voucher_total),
					'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
					'href'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
					'reorder'    => $this->url->link('account/order', 'order_id=' . $result['order_id'], 'SSL')
				);
			}
			
			if(count($data['orders']) > 0){
				$json["data"] = $data;
			}else {
				$json["success"] = false;
				$json["error"] = "No customer order found";
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

	public function getOrder($order_id) { 
		
		$json = array('success' => true);

		$this->language->load('account/order');


		if (!$this->customer->isLogged()) {
			$json["error"] = "User is not logged in";
			$json["success"] = false;
		}
		
		if($json["success"]){
			$this->load->model('account/order');

			$order_info = $this->model_account_order->getOrder($order_id);

			if ($order_info) {

				if ($order_info['invoice_no']) {
					$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$data['invoice_no'] = '';
				}

				$data['order_id'] = $order_id;
				$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);

				$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$data['payment_method'] = $order_info['payment_method'];

				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);

				$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$data['shipping_method'] = $order_info['shipping_method'];

				$data['products'] = array();

				$products = $this->model_account_order->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						}

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
						);					
					}

					$data['products'][] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
						'return'   => $this->url->link('account/return/insert', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL')
					);
				}

				// Voucher
				$data['vouchers'] = array();

				$vouchers = $this->model_account_order->getOrderVouchers($order_id);

				foreach ($vouchers as $voucher) {
					$data['vouchers'][] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$data['totals'] = $this->model_account_order->getOrderTotals($order_id);

				$data['comment'] = nl2br($order_info['comment']);

				$data['histories'] = array();

				$results = $this->model_account_order->getOrderHistories($order_id);

				foreach ($results as $result) {
					$data['histories'][] = array(
						'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
						'status'     => $result['status'],
						'comment'    => nl2br($result['comment'])
					);
				}

				$json["data"] = $data;
			}else {
					$json['success']     = false;
					$json['error']       = "The specified order does not exist.";
			}

			

			if ($this->debugIt) {
				echo '<pre>';
				print_r($json);
				echo '</pre>';
			} else {
				$this->response->setOutput(json_encode($json));
			}			
		}
	}

	public function reorder($order_id) {

		$json = array('success' => true);
		
		if (!$this->customer->isLogged()) {
			$json["error"] = "User is not logged in";
			$json["success"] = false;
		}

		$this->language->load('account/order');

		$this->load->model('account/order');

		
		if($json["success"]){
			/*reorder*/
			if (isset($order_id)) {

				$order_info = $this->model_account_order->getOrder($order_id);

				if ($order_info) {

					$reorder = true;

					$order_products = $this->model_account_order->getOrderProducts($order_id);

					foreach ($order_products as $order_product) {
						$option_data = array();

						$order_options = $this->model_account_order->getOrderOptions($order_id, $order_product['order_product_id']);

						foreach ($order_options as $order_option) {
							if ($order_option['type'] == 'select' || $order_option['type'] == 'radio') {
								$option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
							} elseif ($order_option['type'] == 'checkbox') {
								$option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
							} elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
								$option_data[$order_option['product_option_id']] = $order_option['value'];	
							} elseif ($order_option['type'] == 'file') {
								$option_data[$order_option['product_option_id']] = $this->encryption->encrypt($order_option['value']);
							}
						}

						$this->session->data['error'] = sprintf($this->language->get('text_success'), $order_id);

						$this->cart->add($order_product['product_id'], $order_product['quantity'], $option_data);
					}

				}else {
					$json['success']     = false;
					$json['error']       = "The specified order does not exist.";
				}
			}else {
					$json['success']     = false;
					$json['error']       = "The specified order does not exist.";
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
	* ORDERS FUNCTIONS
	*/	
	public function orders() {

		$this->checkPlugin();
		

		if ( $_SERVER['REQUEST_METHOD'] === 'GET' ){
			//get order details
			if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
				$this->getOrder($this->request->get['id']);
			}else {
				//get order list
				$this->listOrders();
			}
		}else if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
			//reorder
			if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
				$this->reorder($this->request->get['id']);
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
