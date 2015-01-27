<?php /* Opencart Module v1.0 for Citrus Payment Gateway - Copyrighted file (viatechs.in) - Please do not modify/refactor/disasseble/extract any or all part content  */ ?>
<?php 
	class ControllerPaymentCitrus extends Controller 
	{
		private $error = array();
		
		public function index() 
		{
			$this->load->language('payment/citrus');
			$this->document->setTitle($this->language->get('heading_title'));
			$this->load->model('setting/setting');
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) 
			{
				$this->model_setting_setting->editSetting('citrus', $this->request->post);
				$this->session->data['success'] = $this->language->get('text_success');
				$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
			}
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['text_enabled'] = $this->language->get('text_enabled');
			$this->data['text_disabled'] = $this->language->get('text_disabled');
			$this->data['entry_module'] = $this->language->get('entry_module');
			$this->data['entry_module_id'] = $this->language->get('entry_module_id');
			$this->data['entry_vanityurl'] = $this->language->get('entry_vanityurl');
			$this->data['entry_access_key'] = $this->language->get('entry_access_key');
			$this->data['entry_secret_key'] = $this->language->get('entry_secret_key');
			$this->data['entry_status'] = $this->language->get('entry_status');
			$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$this->data['entry_order_status'] = $this->language->get('entry_order_status');
			$this->data['help_encryption'] = $this->language->get('help_encryption');
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['breadcrumbs'] = array();   
			$this->data['breadcrumbs'][] = array('text'=> $this->language->get('heading_title'),'href'=> $this->url->link('payment/citrus', 'token=' . $this->session->data['token'], 'SSL'),'separator' => ' :: ');
			$this->data['action'] = $this->url->link('payment/citrus', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['citrus_module'] = '';
			$this->data['citrus_vanityurl'] = '';
			$this->data['citrus_access_key'] = '';
			$this->data['citrus_secret_key'] = '';
			$this->data['citrus_sort_order'] = '';
			if (isset($this->request->post['citrus_order_status_id'])) 
			{
				$this->data['citrus_order_status_id'] = $this->request->post['citrus_order_status_id'];
			} 
			else 
			{
				$this->data['citrus_order_status_id'] = $this->config->get('citrus_order_status_id'); 
			} 
			$this->load->model('localisation/order_status');
			$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			if (isset($this->request->post['citrus_module'])) 
			{
				$this->data['citrus_module'] = $this->request->post['citrus_module'];
			} 
			else 
			{
				$this->data['citrus_module'] = $this->config->get('citrus_module');
			}
			if (isset($this->request->post['citrus_vanityurl'])) 
			{
				$this->data['citrus_vanityurl'] = $this->request->post['citrus_vanityurl'];
			} 
			else 
			{
				$this->data['citrus_vanityurl'] = $this->config->get('citrus_vanityurl');
			} 
			if (isset($this->request->post['citrus_access_key'])) 
			{
				$this->data['citrus_access_key'] = $this->request->post['citrus_access_key'];
			} 
			else 
			{
				$this->data['citrus_access_key'] = $this->config->get('citrus_access_key');
			}
			if (isset($this->request->post['citrus_secret_key'])) 
			{
				$this->data['citrus_secret_key'] = $this->request->post['citrus_secret_key'];
			} 
			else 
			{
				$this->data['citrus_secret_key'] = $this->config->get('citrus_secret_key');
			}
			if (isset($this->request->post['citrus_status'])) 
			{
				$this->data['citrus_status'] = $this->request->post['citrus_status'];
			} 
			else 
			{
				$this->data['citrus_status'] = $this->config->get('citrus_status');
			}
			if (isset($this->request->post['citrus_sort_order'])) 
			{
				$this->data['citrus_sort_order'] = $this->request->post['citrus_sort_order'];
			} 
			else 
			{
				$this->data['citrus_sort_order'] = $this->config->get('citrus_sort_order');
			}
			$this->template = 'payment/citrus.tpl';
			$this->children = array('common/header','common/footer'); 
			$this->response->setOutput($this->render());
		}
		
		private function validate() 
		{
			if (!$this->user->hasPermission('modify', 'payment/citrus')) 
			{
				$this->error['warning'] = $this->language->get('error_permission');
			}
			if (empty($this->request->post['citrus_module'])) 
			{
				$this->error['citrus_module'] = $this->language->get('error_module');
			}
			if (empty($this->request->post['citrus_vanityurl'])) 
			{
				$this->error['citrus_vanityurl'] = $this->language->get('error_vanityrul');
			}
			if (empty($this->request->post['citrus_access_key'])) 
			{
				$this->error['citrus_access_key'] = $this->language->get('error_accesskey');
			}
			if (empty($this->request->post['citrus_secret_key'])) 
			{
				$this->error['citrus_secret_key'] = $this->language->get('error_secretkey');
			}
			if (count($this->error) > 0)
			{  
				foreach($this->error as $k=>$v)  
				{        
					$this->data['error_'.$k] = $v;    
				}
			}
			if (!$this->error) {return true;} else {return false;}
		}
	}
?>