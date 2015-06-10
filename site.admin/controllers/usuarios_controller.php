<?php
class UsuariosController extends AppController {

	var $name = 'Usuarios';
	var $allowedActions = array('login', 'logout',);

	function login_refresh(){
		$this->layout = 'ajax';
		$this->autoRender = false;
		$this->Session->renew();
		echo 1;
	}
	
	function login(){
		if (!$this->Session->read('Auth.Usuario')){
			if (empty($this->data))$this->Session->delete('Message.auth');

			if ( $this->RequestHandler->isAjax()  ){
				$this->layout = 'ajax';
				$this->set('data', array('valid' => 0, 'error' => __('Usuário/senha incorretos. Por favor, tente novamente.', true)));
				$this->render('/elements/json');
				return;
			}
			
			$this->layout = 'login';
		}else{
			if ( $this->RequestHandler->isAjax()  ){
				$this->layout = 'ajax';
				$this->set('data', array('valid' => true, 'msg' => __('Autenticação realizada com sucesso.', true), 'redirect' => Router::url('/')));
				$this->render('/elements/json');
				return;
			}
			
			$this->redirect($this->Auth->redirect());
		}
	}

	function logout(){
		$this->redirect($this->Auth->logout()); // Efetuamos logout
	}

	function change_pass(){
		if (!empty($this->data)) {
			
			$senha = $this->data['Usuario']['senha'];
			$this->data = $this->Usuario->read(null, $this->Auth->user('id'));
			$this->data['Usuario']['senha'] = Security::hash($senha, null, true);

			if($this->Usuario->save($this->data)) {
				$this->Session->setFlash(__('Sua senha foi atualizada com sucesso!', true),'default',array('class'=>'message_success'));
				$this->redirect('logout');
			} else {
				$this->Session->setFlash(__('Sua senha não foi atualizada. Por favor, verifique os erros apresentados.', true),'default',array('class'=>'message_error'));
			}
		}
	}
	
	function index() {
		$this->_admin_index();
	}

	function add() {
		$this->_admin_add();
	}

	function edit($id = null) {
		$this->_admin_edit($id);
	}

	function delete($id = null) {
		$this->_admin_delete($id);
	}

}
