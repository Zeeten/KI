<?php

class ControllerApiCartadd extends Controller  { 

    public function addtocart() {
        $this->language->load('checkout/cart');

        $json = array();

        $product_id = 0;

        if (isset($this->request->get['pid'])) {
            $product_id = $this->request->get['pid'];
        } 
        
        $this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->getProduct($product_id);
        
        if ($product_info) {  
            
            if (isset($this->request->get['qty'])) {
                $quantity = $this->request->get['qty'];
            } else {
                $quantity = 1;
            }

            if (isset($this->request->get['option'])) {
                $option = array_filter($this->request->get['option']);
            } else {
                $option = array();  
            }
            
            if (isset($this->request->get['profile_id'])) {
                $profile_id = $this->request->get['profile_id'];
            } else {
                $profile_id = 0;
            }
          
            $product_options = $this->model_catalog_product->getProductOptions($product_id);
            
            foreach ($product_options as $product_option) {
                if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
                    $json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
                }
            }
            
            $profiles = $this->model_catalog_product->getProfiles($product_info['product_id']);
            
            if ($profiles) {
                $profile_ids = array();
                
                foreach ($profiles as $profile) {
                    $profile_ids[] = $profile['profile_id'];
                }
                
                if (!in_array($profile_id, $profile_ids)) {
                    $json['error']['profile'] = $this->language->get('error_profile_required');
                }
            }
            
            if (!$json) {
                $product_options = $this->model_catalog_product->getProduct($product_id);
                
                if($product_options['quantity']>0){

                $this->cart->add($product_id, $quantity, $option, $profile_id);
                $json['suucess'] = 'true';
                $json['msg'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' .$product_id), $product_info['name'], $this->url->link('checkout/cart'));
                
                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);
                
                // Totals
                $this->load->model('setting/extension');
                
                $total_data = array();                  
                $total = 0;
                $taxes = $this->cart->getTaxes();
                
                // Display prices
                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $sort_order = array(); 
                    
                    $results = $this->model_setting_extension->getExtensions('total');
                    
                    foreach ($results as $key => $value) {
                        $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
                    }
                    
                    array_multisort($sort_order, SORT_ASC, $results);
                    
                    foreach ($results as $result) {
                        if ($this->config->get($result['code'] . '_status')) {
                            $this->load->model('total/' . $result['code']);

                            $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                        }
                        
                        $sort_order = array(); 

                        foreach ($total_data as $key => $value) {
                            $sort_order[$key] = $value['sort_order'];
                        }

                        array_multisort($sort_order, SORT_ASC, $total_data);            
                    }
                }
                
                $json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
                }else{
                    $json['suucess'] = 'false';
                    $json['error'] = 'Sorry, Book is in out of stock.';
                }
            } else {
                $json['suucess'] = 'false';
                $json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->get['pid']));
            }
        }else{
            $json['suucess'] = 'false';
            $json['error'] = 'Id is not exist';
        }
        $this->response->setOutput(json_encode($json));     
    }
    
    function __call( $methodName, $arguments ) {
        call_user_func(array($this, "addtocart"), $arguments);
    }
}
?>