<?php

class ControllerApiForgetpassword extends Controller {

    private $debug = false;
    
    public function forgetpwd() {
        $this->load->model('account/customer');
        $json = array('success' => false);

        $customer = null;

        if (isset($this->request->get['email'])) {
            $email = $this->request->get['email'];
            
            $customer = $this->model_account_customer->getCustomerByEmail($email);

            if($customer!=null){

                $this->language->load('account/forgotten');
                $this->document->setTitle($this->language->get('heading_title'));
                
                $this->language->load('mail/forgotten');
                $password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
                
                $this->model_account_customer->editPassword($customer['email'], $password);
                
                $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
                
                $message  = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
                $message .= $this->language->get('text_password') . "\n\n";
                $message .= $password;

                $mail = new Mail();
                $mail->protocol = $this->config->get('config_mail_protocol');
                $mail->parameter = $this->config->get('config_mail_parameter');
                $mail->hostname = $this->config->get('config_smtp_host');
                $mail->username = $this->config->get('config_smtp_username');
                $mail->password = $this->config->get('config_smtp_password');
                $mail->port = $this->config->get('config_smtp_port');
                $mail->timeout = $this->config->get('config_smtp_timeout');             
                $mail->setTo($customer['email']);
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
                $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                $mail->send();

                $json = array('success' => true);
                $json['message'] = 'New password is sent on your email.';
            }else{
                $json['error'] = 'Invalid email';
            }
        }
 
    if ($this->debug) {
        echo '<pre>';
        print_r($json);
    } else {
        $this->response->setOutput(json_encode($json));
    }
}

function __call( $methodName, $arguments ) {
    call_user_func(array($this, "forgetpwd"), $arguments);
}
}

?>