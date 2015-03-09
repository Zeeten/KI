<?php
define('OCM_VERSION', '2.0');
define('OCM_EDITION', '0');

defined('ORDER_ABANDONED')         || define('ORDER_ABANDONED', '0');           // abbandonato
defined('ORDER_PENDING')           || define('ORDER_PENDING', '1');             // inattesa di elaborazione
defined('ORDER_PROCESSING')        || define('ORDER_PROCESSING', '2');          // in preparazione
defined('ORDER_SHIPPED')           || define('ORDER_SHIPPED', '3');             // spedito
defined('ORDER_COMPLETE')          || define('ORDER_COMPLETE', '5');            // completo
defined('ORDER_CANCELED')          || define('ORDER_CANCELED', '7');            // cancellato
defined('ORDER_DENIED')            || define('ORDER_DENIED', '8');              // non autorizzato
defined('ORDER_CANCELED_REVERSAL') || define('ORDER_CANCELED_REVERSAL', '9');   // storno cancellato
defined('ORDER_FAILED')            || define('ORDER_FAILED', '10');             // pagamento fallito
defined('ORDER_REFUNDED')          || define('ORDER_REFUNDED', '11');           // rimborsato
defined('ORDER_REVERSED')          || define('ORDER_REVERSED', '12');           // stornato
defined('ORDER_CHARGEBACK')        || define('ORDER_CHARGEBACK', '13');         // contenzioso, richiesta di annulamento della transazione economica
defined('ORDER_EXPIRED')           || define('ORDER_EXPIRED', '14');            // scaduto
defined('ORDER_PROCESSED')         || define('ORDER_PROCESSED', '15');          // processato
defined('ORDER_VOIDED')            || define('ORDER_VOIDED', '16');             // annullato

define('DLMOBILE_PAGE_ROWS', '10');

define('ORDERLIST_WITH_COMMENT', 'TRUE');


class ControllerDLMobileServer extends Controller
{   
	private $new_order_status = array(ORDER_PENDING, ORDER_PROCESSING);
	private $valid_order_status = array(ORDER_PENDING, ORDER_PROCESSING, ORDER_SHIPPED, ORDER_COMPLETE, ORDER_CANCELED_REVERSAL, ORDER_PROCESSED);
	private $valid_sales_status = array(ORDER_PENDING, ORDER_PROCESSING, ORDER_SHIPPED, ORDER_COMPLETE, ORDER_CANCELED_REVERSAL, ORDER_PROCESSED);

	public function __construct($registry)
	{
		parent::__construct($registry);
		
		header('Content-Type:text/plain;charset=utf-8');
		
		$ignore = array('formatCurrency', 'getStrings');		
		
		$route = '';
		
		if (isset($this->request->get['route'])) {
			$part = explode('/', $this->request->get['route']);
		
			if (isset($part[0])) {
				$route .= $part[0];
			}
		
			if (isset($part[1])) {								
				$route .= '/' . $part[1];
			}
			
			if (isset($part[2]) && in_array($part[2], $ignore)) {
				return;
			}			
		}
		
		// check credential
		
		if(!isset($this->request->get['username']) || !isset($this->request->get['password']) || !$this->user->login($this->request->get['username'], $this->request->get['password'])) {
			$this->load_language('common/login');
			$this->data['error'] = $this->language->get('error_login');
		}
		
		// check permission
				
		if($this->user->isLogged() && !$this->user->hasPermission('access', $route)) {
			$this->load_language('error/permission');
			$this->data['error'] = $this->language->get('text_permission');
		}
		
		// ...
		
		if(isset($this->data['error'])) {
			echo json_encode($this->data /*, JSON_NUMERIC_CHECK*/);
			die;
		}
	}
	
	public function load_language($file)
	{
		if(strcmp(substr(VERSION, 0, 5), '1.5.5') < 0) {
			$this->load->language($file);
		}
		else {
			$this->language->load($file);
		}
	}
	
 	public function index()
	{
		$result = array();
		
		$result['ocm_version'] = OCM_VERSION;
		$result['ocm_edition'] = OCM_EDITION;
		$result['oc_version']  = VERSION;
		$result['http_image']  = HTTP_CATALOG.'image';
		
		$result['store_name'] = $this->config->get('config_name');
	
		// ordini
		//$query = $this->db->query("SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND o.order_status_id IN (" . implode(',', $this->new_order_status) . ") AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o");
		$query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE order_status_id IN (" . implode(',', $this->new_order_status) . ")");
		$result['new_orders'] = array(); 
		foreach ($query->rows as $row){
			$result['new_orders'][] = $row['order_id'];			
		}
                		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id IN (" . implode(',', $this->valid_order_status) . ") AND DATE(date_added) = DATE(NOW())");
		$result['today_orders'] = $query->row['total'];

		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id IN (" . implode(',', $this->valid_order_status) . ") AND WEEK(date_added) = WEEK(NOW()) AND YEAR(date_added) = YEAR(NOW())");
		$result['week_orders'] = $query->row['total'];

		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id IN (" . implode(',', $this->valid_order_status) . ") AND MONTH(date_added) = MONTH(NOW()) AND YEAR(date_added) = YEAR(NOW())");
		$result['month_orders'] = $query->row['total'];
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id IN (" . implode(',', $this->valid_order_status) . ") AND YEAR(date_added) = YEAR(NOW())");
		$result['year_orders'] = $query->row['total'];
		
		// reclami
		$query = $this->db->query("SELECT return_id FROM `" . DB_PREFIX . "return` WHERE return_status_id = '" . (int)$this->config->get('config_return_status_id'). "'");				
		$result['new_returns'] = array();
		foreach($query->rows as $row) {
			$result['new_returns'][] = $row['return_id'];
		}		
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE DATE(date_added) = DATE(NOW())");				
		$result['today_returns'] = $query->row['total'];
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE WEEK(date_added) = WEEK(NOW()) AND YEAR(date_added) = YEAR(NOW())");
		$result['week_returns'] = $query->row['total'];
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE MONTH(date_added) = MONTH(NOW()) AND YEAR(date_added) = YEAR(NOW())");
		$result['month_returns'] = $query->row['total'];
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return` WHERE YEAR(date_added) = YEAR(NOW())");
		$result['year_returns'] = $query->row['total'];
		
		// vendite		
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id IN (" . implode(',', $this->valid_sales_status) . ") AND DATE(date_added) = DATE(NOW())");
		$result['today_sales'] = $this->currency->format($query->row['total']);
		
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id IN (" . implode(',', $this->valid_sales_status) . ") AND WEEK(date_added) = WEEK(NOW()) AND YEAR(date_added) = YEAR(NOW())");
		$result['week_sales'] = $this->currency->format($query->row['total']);
		
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id IN (" . implode(',', $this->valid_sales_status) . ") AND MONTH(date_added) = MONTH(NOW()) AND YEAR(date_added) = YEAR(NOW())");
		$result['month_sales'] = $this->currency->format($query->row['total']);
		
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id IN (" . implode(',', $this->valid_sales_status) . ") AND YEAR(date_added) = YEAR(NOW())");
		$result['year_sales'] = $this->currency->format($query->row['total']);
		
		// clienti
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE DATE(date_added) = DATE(NOW())");		
		$result['today_customers'] = $query->row['total'];
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE WEEK(date_added) = WEEK(NOW()) AND YEAR(date_added) = YEAR(NOW())");
		$result['week_customers'] = $query->row['total'];
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE MONTH(date_added) = MONTH(NOW()) AND YEAR(date_added) = YEAR(NOW())");
		$result['month_customers'] = $query->row['total'];
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE YEAR(date_added) = YEAR(NOW())");
		$result['year_customers'] = $query->row['total'];
		
		// visitor counter
		$this->load->model('setting/extension');
		if(in_array('visitorcounter', $this->model_setting_extension->getInstalled('module')) && file_exists(DIR_APPLICATION . 'model/dlextension/visitorcounter.php')) {
			$this->load->model('dlextension/visitorcounter');
			
			$today_counter = $this->model_dlextension_visitorcounter->todayCounter();
			$week_counter = $this->model_dlextension_visitorcounter->weekCounter();
			$month_counter = $this->model_dlextension_visitorcounter->monthCounter();
			$year_counter = $this->model_dlextension_visitorcounter->yearCounter();
			
			$result['today_visitors'] = $today_counter['visitors'];
			$result['today_pages'] = $today_counter['pages'];
			$result['week_visitors'] = $week_counter['visitors'];
			$result['week_pages'] = $week_counter['pages'];
			$result['month_visitors'] = $month_counter['visitors'];
			$result['month_pages'] = $month_counter['pages'];
			$result['year_visitors'] = $year_counter['visitors'];
			$result['year_pages'] = $year_counter['pages'];
		}
		
		if (strnatcmp(phpversion(), '5.4.0') >= 0) {
			$json_result = json_encode($result,/* JSON_NUMERIC_CHECK | */ JSON_UNESCAPED_UNICODE);
		}
		else {
			$json_result = json_encode($result /*, JSON_NUMERIC_CHECK */);
		}
		
		$this->response->setOutput($json_result);
	}
	
	public function orders()
	{
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 0;
		$filter = isset($this->request->get['filter']) ? $this->request->get['filter'] : array();
		if(!is_array($filter)) {
			$filter = array($filter);
		}		

		$this->load->model('sale/order');
		
		$result = new stdClass();
		$result->orders = $this->model_sale_order->getOrders(array_merge(
			array(
				'start' => DLMOBILE_PAGE_ROWS * $page,
				'limit' => DLMOBILE_PAGE_ROWS,
				'order' => 'DESC'
			),
			$filter
		));
		
		foreach($result->orders as &$item) {
			$item['total'] = $this->currency->format($item['total'], $item['currency_code'], $item['currency_value']);
			
			if(ORDERLIST_WITH_COMMENT) {
				$order_detail = $this->model_sale_order->getOrder($item['order_id']);
				$item['comment'] = $order_detail['comment'];
				$item['payment_company'] = $order_detail['payment_company'];
			}
		}
		
		$this->response->setOutput(json_encode($result));
	}
	
	public function returns()
	{
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 0;
		$filter = isset($this->request->get['filter']) ? $this->request->get['filter'] : array();
		if(!is_array($filter)) {
			$filter = array($filter);
		}
		
		$this->load->model('sale/return');
		
		$result = new stdClass();
		$result->returns = $this->model_sale_return->getReturns(array_merge(
			array(
				'start' => DLMOBILE_PAGE_ROWS * $page,
				'limit' => DLMOBILE_PAGE_ROWS,
				'order' => 'DESC'
			),
			$filter
		));
		
		foreach($result->returns as &$item) {
			$item['customer'] = $item['firstname'] . ' ' . $item['lastname'];
			unset($item['firstname']);
			unset($item['lastname']);
		}
		
		$this->response->setOutput(json_encode($result));
	}
	
	public function orderStatuses()
	{
		$this->load->model('localisation/order_status');
		
		$result = new stdClass();
		$result->orderstatuses = $this->model_localisation_order_status->getOrderStatuses();
		
		$this->response->setOutput(json_encode($result));
	}
	
	public function returnStatuses()
	{
		$this->load->model('localisation/return_status');
		$result = new stdClass();
		$result->returnstatuses = $this->model_localisation_return_status->getReturnStatuses();
		
		$this->response->setOutput(json_encode($result));		
		
	}
	
	public function customers()
	{
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 0;
		$filter = isset($this->request->get['filter']) ? $this->request->get['filter'] : array();
		if(!is_array($filter)) {
			$filter = array($filter);
		}
		
		$this->load->model('sale/customer');
		
		$result = new stdClass();
		$result->customers = $this->model_sale_customer->getCustomers(array_merge(
				array(
						'start' => DLMOBILE_PAGE_ROWS * $page,
						'limit' => DLMOBILE_PAGE_ROWS,
						'order' => 'DESC'
				),
				$filter
		));
		
		foreach($result->customers as &$item) {
			$item["companies"] = array();
			foreach($this->model_sale_customer->getAddresses($item["customer_id"]) as $a) {
				if(trim($a["company"]) == '' || in_array($a["company"], $item["companies"])) continue;
				$item["companies"][] = $a['company'];
			}
		}
		
		$this->response->setOutput(json_encode($result));		
	}
	
	public function customerGroups()
	{
		$this->load->model('sale/customer_group');
		$result = new stdClass();
		$result->customergroups = $this->model_sale_customer_group->getCustomerGroups();
		
		$this->response->setOutput(json_encode($result));		
	}

	public function exec()
	{
		$request = (array)json_decode(htmlspecialchars_decode($this->request->request['request']));
		$result = new stdClass();
		
		foreach ($request as $prop => $obj) {
			if(isset($obj->model) && isset($obj->method)) {
				$model = $obj->model;
				$method = $obj->method;
					
				$params = isset($obj->params) ? $obj->params : array();
				$params = is_array($params) ? $params : array($params);
				
				foreach($params as &$p) {
					if(!is_object($p)) continue;
					$p = (array)$p;
				}
				//echo "params=";print_r($params);
				
				if($model && $method) {
					$this->load->model($model);
					$model_ = 'model_'.str_replace('/', '_',$model);
					$result->{$prop} = call_user_func_array(array($this->$model_, $method), $params);
				}
				else {
					$result->{$prop} = null;
					$result->errors[$prop] = "Model and/or method not specified";
				}
			}
			else if(isset($obj->section)) {
				$section = $obj->section;
				
				$keys = isset($obj->keys) ? $obj->keys : array();
				$keys = is_array($keys) ? $keys : array($keys);
				
				if($section) {
					$this->load_language($section);
					
					$result->{$prop} = array();
					foreach ($keys as $key) {
						$result->{$prop}[$key] = $this->language->get($key);
					}
				}
				else {
					$result->{$prop} = null;
					$result->errors[$prop] = "Section not specified";
				}
			}
			else if(isset($obj->currency)) {
				$result->{$prop} = $this->formatCurrencyFromJson($obj->currency);
			}
			else {
				$result->{$prop} = null;
				$result->errors[$prop] = "Unknown command";
			}
		}		

		$this->response->setOutput(json_encode($result));		
	}
	
	public function formatCurrency()
	{		
		$result = $this->formatCurrencyFromJson(json_decode(htmlspecialchars_decode($this->request->request['request'])));

		$this->response->setOutput(json_encode($result));
	}
	
	public function formatCurrencyFromJson($jsonRequest)
	{
		$request = (array)$jsonRequest;
		$result = new stdClass();
		
		foreach ($request as $prop => $obj) {
			if(isset($obj->number)) {
				$number = $obj->number;
				
				// optionals parameters
				$currency = isset($obj->currency) ? $obj->currency : '';
				$value = isset($obj->value) ? $obj->value : '';
				$format = isset($obj->format) ? $obj->format : true;
				
				$result->{$prop} = $this->currency->format($number, $currency, $value, $format);
			}			
		}
		
		return $result;
	}
	
/*	
	public function formatCurrency()
	{
		$number = isset($this->request->get['number']) ? $this->request->get['number'] : array();		
		$number = is_array($number) ? $number : array($number);
		
		// optionals parameters
		$currency = isset($this->request->get['currency']) ? $this->request->get['currency'] : '';
		$value = isset($this->request->get['value']) ? $this->request->get['value'] : '';
		$format = isset($this->request->get['format']) ? (boolean)$this->request->get['format'] : true;
		
		$result = new stdClass();
		$result->currency = array();
		
		foreach ($number as $n) {
			$result->currency[] = $this->currency->format($n, $currency, $value, $format);
		}
		
		$this->response->setOutput(json_encode($result));
	}	
	*/
}
?>