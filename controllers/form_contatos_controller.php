<?php
class FormContatosController extends AppController {

	var $name = 'FormContatos';
	var $components = array('Email');
	var	$cacheAction = false;
	
	function index_ok(){
	}

	function index(){
		if ($this->RequestHandler->isPost()) {
			$this->FormContato->set($this->data);
			
			if ($this->FormContato->validates()) {

				//Envia email usando Email component
				$this->Email->template = 'post_data';
				$this->Email->charset = 'utf-8';
				$this->Email->to = Configure::read('form_contato.to');
				$this->Email->subject = Configure::read('form_contato.subject');
				$this->Email->from = $this->data['FormContato']['email'];
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
				
				if(true == $this->Email->send()){
					$this->render('index_ok');
					return true;

/*
					$this->Session->setFlash(__('Mensagem enviada com sucesso!', true),'default',array('class'=>'message_success'), 'contato');
					$this->redirect('/'.(!isset($this->params['originalArgs']) ? 
						$this->params['url']['url']
						:
						$this->params['originalArgs']['params']['url']['url']
					));
*/
				}else{
					$this->Session->setFlash(__('Ocorreu um erro ('.$this->Email->smtpError.') ao enviar a mensagem!<br />Por favor, tente novamente.', true),'default',array('class'=>'message_error'),'form');
				}
			}else{
				$this->Session->setFlash(__('Por favor, preencha o formulário corretamente!', true),'default',array('class'=>'message_error'), 'form');
			}
		}
	}
}