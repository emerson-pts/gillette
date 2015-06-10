<?php
class JogadoresController extends AppController {

	var $name = 'Jogadores';
	
	function index(){
		//Carrega jogadores
		$jogadores = $this->Jogador->find('all',array(
			'conditions' => array(
				'Jogador.show_in_list' => 1,
			),
			'order' => array(
				'Jogador.ranking' => 'ASC'
			),

		));
		//Não encontrou...
		if (!$jogadores){
			$this->Session->setFlash(__('Ops! Página não encontrada', true),'default',array('class'=>'message_error'));
			$this->render('/pages/blank');
			return;
		}

		$this->set('options', $this->Jogador->options);
		$this->set(compact('jogadores'));
	}
}