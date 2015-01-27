<?php /* Opencart Module v1.0 for Citrus Payment Gateway - Copyrighted file (viatechs.in) - Please do not modify/refactor/disasseble/extract any or all part content  */ ?>
<?php 
	class ControllerCheckoutFail extends Controller 
	{ 
		public function index() 
		{    
			$this->language->load('checkout/fail');
			$this->document->setTitle($this->language->get('heading_title'));
			$this->data['breadcrumbs'] = array();      
			$this->data['breadcrumbs'][] = array('href'=> $this->url->link('common/home'),
			'text'=> $this->language->get('text_home'),'separator' => false);
			$this->data['breadcrumbs'][] = array('href'=> $this->url->link('checkout/cart'),
			'text'=> $this->language->get('text_basket'),'separator' => $this->language->get('text_separator'));
			$this->data['breadcrumbs'][] = array('href'=> $this->url->link('checkout/checkout', '', 'SSL'),'text'=> 
			$this->language->get('text_checkout'),'separator' => 
			$this->language->get('text_separator'));
			$this->data['breadcrumbs'][] = array('href'=> $this->url->link('checkout/success'), 
			'text'=> $this->language->get('text_success'),'separator' => 
			$this->language->get('text_separator'));
			$this->data['heading_title'] = $this->language->get('heading_title');  
			$this->data['text_message'] = sprintf($this->language->get('text_message'), 
			$this->url->link('information/contact'));    
			$this->data['button_continue'] = $this->language->get('button_continue');    
			$this->data['continue'] = $this->url->link('common/home');
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) 
			{
				$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
			} 
			else 
			{
				$this->template = 'default/template/common/fail.tpl';
			}
			$this->children = array('common/column_left','common/column_right','common/content_top','common/content_bottom','common/footer','common/header');
			$this->response->setOutput($this->render()); 
		}
	}
?>