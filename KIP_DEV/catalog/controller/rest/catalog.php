<?php
class ControllerRestCatalog extends Controller {
	private $debugIt = false;
	/*
	* Get products
	*/
	public function products() {
		$this->checkPlugin();
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('tool/image');
		$json = array('success' => true, 'products' => array());
		/*check category id parameter*/
		if (isset($this->request->get['category'])) {
			$category_id = $this->request->get['category'];
		} else {
			$category_id = 0;
		}
		$products = $this->model_catalog_product->getProducts(array(
			'filter_category_id'        => $category_id
		));
		foreach ($products as $product) {
			if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], 1536 , 600);
			} else {
				$image = false;
			}
			if ((float)$product['special']) {
				$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}

			$product_category = $this->model_catalog_product->getCategories($product['product_id']);

			$category = array();

			foreach ($product_category as $prodcat) {

			$category_info = $this->model_catalog_category->getCategory($prodcat['category_id']);
				if($category_info !=null ){
					$category[] = array(
						'id' => $category_info['category_id'],
						'name' => $category_info['name']
					);				
				}
			}
		
			$json['products'][] = array(
					'id'			=> $product['product_id'],
					'name'			=> $product['name'],
					'author_id'     => $product['jan'],
					'description'	=> str_replace("\n"," ",str_replace("\r","",strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')))),
					'price'			=> $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')),
					'currency'		=> $this->session->data['currency'],
					'image'			=> $image,
					'manufacturer_id' => $product['manufacturer_id'],
            		'manufacturer' => $product['manufacturer'],
            		'attribute_groups' => $this->model_catalog_product->getProductAttributes($product['product_id']),
					'special'		=> $special,
					'rating'		=> $product['rating'],
					'reviews'     	=> $product['reviews'],
					'category' 		=> $category
				);

			$category = array();
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
	* Get orders
	*/
	public function orders() {
		$this->checkPlugin();
	
		$orderData['orders'] = array();
		$this->load->model('account/order');
		/*check offset parameter*/
		if (isset($this->request->get['offset']) && $this->request->get['offset'] != "" && ctype_digit($this->request->get['offset'])) {
			$offset = $this->request->get['offset'];
		} else {
			$offset 	= 0;
		}
		/*check limit parameter*/
		if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit 	= 10000;
		}
		
		/*get all orders of user*/
		$results = $this->model_account_order->getAllOrders($offset, $limit);
		
		$orders = array();
		if(count($results)){
			foreach ($results as $result) {
				$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
				$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);
				$orders[] = array(
						'order_id'		=> $result['order_id'],
						'name'			=> $result['firstname'] . ' ' . $result['lastname'],
						'status'		=> $result['status'],
						'date_added'	=> $result['date_added'],
						'products'		=> ($product_total + $voucher_total),
						'total'			=> $result['total'],
						'currency_code'	=> $result['currency_code'],
						'currency_value'=> $result['currency_value'],
				);
			}
			$json['success'] 	= true;
			$json['orders'] 	= $orders;
		}else {
			$json['success'] 	= false;
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
		$json = array("success"=>false);
		/*check rest api is enabled*/
		if (!$this->config->get('rest_api_status')) {
			$json["error"] = 'API is disabled. Enable it!';
		}
		
		/*validate api security key*/
		if ($this->config->get('rest_api_key') && (!isset($this->request->get['key']) || $this->request->get['key'] != $this->config->get('rest_api_key'))) {
			$json["error"] = 'Invalid secret key';
		}
		
		if(isset($json["error"])){
			$this->response->addHeader('Content-Type: application/json');
			echo(json_encode($json));
			exit;
		}else {
			$this->response->setOutput(json_encode($json));			
		}	
	}	
}