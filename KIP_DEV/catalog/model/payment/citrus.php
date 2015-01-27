<?php /* Opencart Module v1.0 for Citrus Payment Gateway - Copyrighted file (viatechs.in) - Please do not modify/refactor/disasseble/extract any or all part content  */ ?>
<?php 
	class ModelPaymentCitrus extends Model 
	{  
		public function getMethod($address, $total) 
		{
			$this->load->language('payment/citrus');
			$method_data = array();
			$status = true;
			if ($status) 
			{        
				$method_data = array('code'=> 'citrus','title'=> $this->language->get('text_title'),
				'sort_order' => $this->config->get('citrus_sort_order'));
			}       
			return $method_data; 
		}
	}
?>