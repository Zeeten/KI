<?php /* Opencart Module v1.0 for Citrus Payment Gateway - Copyrighted file (viatechs.in) - Please do not modify/refactor/disasseble/extract any or all part content  */ ?>
<?php 
set_include_path('citrus/lib'.PATH_SEPARATOR.get_include_path());
require_once("citrus/lib/CitrusPay.php");
require_once("citrus/lib/Zend/Crypt/Hmac.php");
	class ControllerPaymentcitrus extends Controller 
	{
	
		function generateHmacKey($data, $apiKey=null){
			$hmackey = Zend_Crypt_Hmac::compute($apiKey, "sha1", $data);
			return $hmackey;
		}	
		
		protected function index() 
		{
			global $_SERVER;
			$this->data['button_confirm'] = $this->language->get('button_confirm');
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']); 
			$this->data['sid'] = $this->config->get('citrus_account');
			
			$this->load->language('payment/citrus');
			$this->data['entry_netb'] = $this->language->get('entry_netb'); 
			$this->data['entry_bname'] = $this->language->get('entry_bname');
			$this->data['entry_bselect'] = $this->language->get('entry_bselect');
			$this->data['entry_cc'] = $this->language->get('entry_cc');
			$this->data['entry_dc'] = $this->language->get('entry_dc');
			$this->data['entry_cselect'] = $this->language->get('entry_cselect');
			$this->data['entry_cname'] = $this->language->get('entry_cname');
			$this->data['entry_cno'] = $this->language->get('entry_cno');
			$this->data['entry_date'] = $this->language->get('entry_date');
			$this->data['entry_type'] = $this->language->get('entry_type');
			$this->data['entry_cvv'] = $this->language->get('entry_cvv');
			$this->data['citrus_module'] = $this->config->get('citrus_module');
			$this->data['citrus_vanityurl'] = $this->config->get('citrus_vanityurl');
			$this->data['citrus_access_key'] = $this->config->get('citrus_access_key');
			$this->data['citrus_secret_key'] = $this->config->get('citrus_secret_key');
			$this->data['citrus_merchant_trans_id'] = 'ord'.$order_info['order_id'];
			$this->data['currency'] = $this->config->get('config_currency');
			$total = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
			$ntotal = $this->currency->convert( $total, $this->currency->getCode(),$this->data['currency']);
			$this->data['total'] = sprintf("%.2f", $ntotal);
			
			$this->data['firstname'] = $order_info['payment_firstname'];
			$this->data['lastname'] = $order_info['payment_lastname'];
			$this->data['addr1'] = $order_info['payment_address_1'];
			$this->data['city'] = $order_info['payment_city'];
			$this->data['state'] = $order_info['payment_zone'];
			$this->data['zip'] = $order_info['payment_postcode'];
			$this->data['country'] = $order_info['payment_country'];
			$this->data['email'] = $order_info['email'];
			$this->data['phone'] = $order_info['telephone'];
			
			CitrusPay::setApiKey($this->data['citrus_secret_key'],$this->data['citrus_module']);
			$vanityUrl = $this->data['citrus_vanityurl'];
			$currency =$this->data['currency'];	
			$merchantTxnId = $this->data['citrus_merchant_trans_id'];
			$orderAmount = $this->data['total'];		
			$data = "$vanityUrl$orderAmount$merchantTxnId$currency";
			
			$secSignature = $this->generateHmacKey($data,CitrusPay::getApiKey());
			$action = CitrusPay::getCPBase()."$vanityUrl"; 
			
			$this->data['action']=$action;
			$this->data['secSignature']=$secSignature;
			$this->data['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.dirname($_SERVER['PHP_SELF']).'/';
			$this->data['products'] = array();
			$products = $this->cart->getProducts();     
			foreach ($products as $product) 
			{
				$this->data['products'][] = array(
				'product_id'  => $product['product_id'],
				'name' => $product['name'],
				'description' => $product['name'],
				'quantity'    => $product['quantity'],
				'price'  => $this->currency->format($product['price'], 
				$order_info['currency_code'], $order_info['currency_value'], false));
			}
			$this->data['lang'] = $this->session->data['language'];
			$this->data['redir_url'] = $this->url->link('payment/citrus/callback', '', 'SSL');
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/citrus.tpl')) 
			{
				$this->template = $this->config->get('config_template') . '/template/payment/citrus.tpl';
			} 
			else 
			{
				$this->template = 'default/template/payment/citrus.tpl';
			}
			$this->render();
		}
		
		public function callback() 
		{
			$this->load->model('checkout/order');
			$this->data['citrus_module'] = $this->config->get('citrus_module');
			$this->data['citrus_secret_key'] = $this->config->get('citrus_secret_key');
			CitrusPay::setApiKey($this->data['citrus_secret_key'],$this->data['citrus_module']);
			
			if (strtoupper($_POST['TxStatus']) == 'SUCCESS')
			{
				//resp signature validation
				$data=$_POST['TxId'].$_POST['TxStatus'].$_POST['amount'].$_POST['pgTxnNo'].$_POST['issuerRefNo'].$_POST['authIdCode'].$_POST['firstName'].$_POST['lastName'].$_POST['pgRespCode'].$_POST['addressZip'];
		  		$respSig=$_POST['signature'];
				if($this->generateHmacKey($data,CitrusPay::getApiKey()) == $respSig)
				{
					$this->model_checkout_order->confirm($this->session->data['order_id'], 
					$this->config->get('citrus_order_status_id'));  
					$this->redirect($this->url->link('checkout/success'));  
				}
				else
					$this->redirect($this->url->link('checkout/fail'));	//forged 
			}
			else
			{
				$this->redirect($this->url->link('checkout/fail'));  
			}
		}
		
		
		
		
	}
?>