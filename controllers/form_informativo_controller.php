<?php
class FormInformativoController extends AppController {

	var $name = 'FormInformativo';
	var $components = array('Email');
	var	$cacheAction = false;
	
	var $uses=array('FormInformativo');
		
	function index(){

		if ($this->RequestHandler->isPost()) {
			$this->FormInformativo->set($this->data);
			
			if($this->FormInformativo->validates()){
				
				//Envia email usando Email component
				$this->Email->template = 'post_data';
				$this->Email->charset = 'utf-8';
				$this->Email->to = Configure::read('form_informativo.to');
				$this->Email->subject = Configure::read('form_informativo.subject');
				$this->Email->from = $this->data['FormInformativo']['email'];
				$this->Email->sendAs = 'both';

				//As configurações estão no bootstrap
				$this->Email->smtpOptions = array(
					'auth' 		=> true,
					'timeout'	=> 10, 
					'port'		=> Configure::read('smtp.port'), 
					'host' 		=> Configure::read('smtp.host'), 
					'username' 	=> Configure::read('smtp.username'), 
					'password' 	=> Configure::read('smtp.password'),
				);
				$this->Email->delivery = 'smtp';
				
				
				//SALVA NO BANCO DE DADOS
				$this->FormInformativo->save($this->data);
				
				if(false == $this->Email->send()){
					$this->Session->setFlash(__('Ocorreu um erro ('.$this->Email->smtpError.') ao enviar a mensagem!<br />Por favor, tente novamente.', true),'default',array('class'=>'message_error'),'form');
				}
				
				
				
				
			}else{
				$this->Session->setFlash(__('Por favor, preencha o formulário corretamente!', true),'default',array('class'=>'message_error'), 'form');
			}
		}
		$this->redirect($this->referer());
	}
}