<?php 
class SortableAdminComponent extends Object {
	//called after Controller::beforeFilter()
	function startup(&$controller) {
		$this->controller =& $controller;
	}

	function _move($action, $id, $delta) {
		$this->controller->{$this->controller->modelClass}->lock();
		if(empty($id) || !is_numeric($id)){
			if ($this->controller->RequestHandler->isAjax()) {
				$this->controller->set('data', __('O código do registro não foi informado!', true));
				$this->controller->render('/elements/json');
				return;
			}else{
				$this->controller->Session->setFlash(__('O código do registro não foi informado!', true),'default',array('class'=>'message_error'));
				$this->controller->redirect(array('action' => 'index') + $this->controller->params['named']);
			}
		}
		
		$return = $this->controller->{$this->controller->modelClass}->$action($id, $delta);
		if ($this->controller->RequestHandler->isAjax()) {
			if(!$return){
				$this->controller->set('data', __('Falha na atualização da posição!', true));
			}else{
				$this->controller->set('data', true);
			}
			$this->controller->render('/elements/json');
			return;
		}else{
			if(!$return){
            	$this->controller->Session->setFlash(__('Falha na atualização da posição!', true),'default',array('class'=>'message_error'));
			}else{
            	$this->controller->Session->setFlash(__('Posição atualizada com sucesso!', true),'default',array('class'=>'message_success'));
			}
			$this->controller->redirect(array('action' => 'index') + $this->controller->params['named']);
		}
	}

	function movedown($id = null, $delta = 1) {
		return $this->_move('moveDown', $id, $delta);
	}

	function moveup($id = null, $delta = 1) {
		return $this->_move('moveUp', $id, $delta);
	}
	
	function setposition($id = null, $delta= 1) {
		return $this->_move('setPosition', $id, $delta);
	}

	function fixposition() {
		$this->controller->{$this->controller->modelClass}->fixposition();
      	$this->controller->Session->setFlash(__('Posição reiniciada com sucesso!', true),'default',array('class'=>'message_success'));
		$this->controller->redirect(array('action' => 'index') + $this->controller->params['named']);
	}
} 