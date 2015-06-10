<?php 
class TreeAdminComponent extends Object {
	//called after Controller::beforeFilter()
	function startup(&$controller) {
		$this->controller =& $controller;
	}

	function movedown($id, $delta = 1) {
		$this->controller->{$this->controller->modelClass}->lock();
		$return = $this->controller->{$this->controller->modelClass}->moveDown($id, abs($delta));
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

	function moveup($id, $delta = 1) {
		$this->controller->{$this->controller->modelClass}->lock();
		$return = $this->controller->{$this->controller->modelClass}->moveUp($id, abs($delta));
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
			$this->controller->redirect(array('action' => 'index'));
		}
	}

	function update_parent($data){
		$this->controller->{$this->controller->modelClass}->lock();
		if(!isset($data['id'])){
			$this->controller->set('data', __('O código do registro não foi informado!', true));
			$this->controller->render('/elements/json');
			return;
		}

		if(!isset($data['new_parent'])){
			$this->controller->set('data', __('O código do registro do novo pai não foi informado!', true));
			$this->controller->render('/elements/json');
			return;
		}

		if(!isset($data['new_index'])){
			$this->controller->set('data', __('O código da posição não foi informado!', true));
			$this->controller->render('/elements/json');
			return;
		}

		$model_obj = $this->controller->{$this->controller->modelClass};
		$model_obj->id = $data['id'];
		
		if($data['current_parent'] == $data['new_parent'] && $data['current_index'] == $data['new_index']){
			$this->controller->set('data', true);
			$this->controller->render('/elements/json');
			return;
		}
		
		if(!($model_obj->saveField('parent_id', $data['new_parent']))){
			$this->controller->set('data', __('Falha na atualização da hierarquia!', true));
			$this->controller->render('/elements/json');
			return;
		}

		//Ajusta a posição
		//Se o novo pai tem mais de um filho
		if($model_obj->childcount($data['new_parent'], true) > 1){
			// Se o novo índice for 0 ... 
			if ($data['new_index'] == 0){
				if(!($model_obj->moveup(null, true))){
					$this->controller->set('data', __('Falha na atualização da posição (cód. 1)!', true));
					$this->controller->render('/elements/json');
					return;
				}
			} 
			else {
				// Cálcula o delta para saber a quantidade de posições e a distãncia
				$count = $model_obj->childcount($data['new_parent'], true); //Verifica a quantidade de filhos no pai
				$delta = $count-$data['new_index']-1;
				
				//Se tem necessidade de subir
				if($delta > 0 && !($model_obj->moveup(null, $delta))){
					$this->controller->set('data', __('Falha na atualização da posição (cód. 2)!', true));
					$this->controller->render('/elements/json');
					return;
				}
			}		
		}
		
		$this->controller->set('data', true);
		$this->controller->render('/elements/json');
		return;
	}
} 