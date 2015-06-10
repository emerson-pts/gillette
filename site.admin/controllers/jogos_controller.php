<?php
class JogosController extends AppController {

	var $name = 'Jogos';
	var $components = array('TreeAdmin');

	function beforeRender(){
		parent::beforeRender();
		
		//Se está adicionando e enviou parent_id
		//ou está editando e o parent_id não é vazio
		if(
			(preg_match('/^(add)$/', $this->action) && !empty($this->params['pass'][0]))
			||
			(preg_match('/^(edit)$/', $this->action) && !empty($this->params['pass'][0]) && !empty($this->data['Jogo']['parent_id']))
		){
		
			//Recupera árvore do jogos
			if(!$jogos = $this->Jogo->getpath((!empty($this->data['Jogo']['parent_id']) ? $this->data['Jogo']['parent_id'] : $this->params['pass'][0]), array('parent_id', 'id', 'titulo', 'qtd_jogadores_equipe', ))){
				$this->Session->setFlash(__('Não foi possível recuperar os dados da chave principal. Você não pode criar uma chave a partir desta área.', true), 'default',array('class'=>'message_error'));
				$this->redirect(array('action' => 'index'));
			}
			
			if($jogos[0]['Jogo']['qtd_jogadores_equipe'] == 1){
				unset($this->viewVars['setup']['form']['equipe1_jogador2_id']);
				unset($this->viewVars['setup']['form']['equipe1_jogador2_text']);
				unset($this->viewVars['setup']['form']['equipe2_jogador2_id']);
				unset($this->viewVars['setup']['form']['equipe2_jogador2_text']);
			}
			
			unset($this->viewVars['setup']['form']['fases']);
			unset($this->viewVars['setup']['form']['titulo']);
			unset($this->viewVars['setup']['form']['friendly_url']);
			unset($this->viewVars['setup']['form']['qtd_jogadores_equipe']);
		}else{
			unset($this->viewVars['setup']['form']['data']);
			unset($this->viewVars['setup']['form']['placar']);
			unset($this->viewVars['setup']['form']['equipe1_jogador1_id']);
			unset($this->viewVars['setup']['form']['equipe1_jogador1_text']);
			unset($this->viewVars['setup']['form']['equipe1_jogador2_id']);
			unset($this->viewVars['setup']['form']['equipe1_jogador2_text']);
			unset($this->viewVars['setup']['form']['equipe2_jogador1_id']);
			unset($this->viewVars['setup']['form']['equipe2_jogador1_text']);
			unset($this->viewVars['setup']['form']['equipe2_jogador2_id']);
			unset($this->viewVars['setup']['form']['equipe2_jogador2_text']);
		}

		//Se estiver editando não pode alterar o parent_id
		if(preg_match('/^(edit)$/', $this->action)){
			$this->viewVars['setup']['form']['parent_id']['type'] = 'hidden';
		}
	}
		
	function movedown($id, $delta = 1) {
		$this->TreeAdmin->movedown($id, $delta);
	}
	
	function moveup($id, $delta = 1) {
		$this->TreeAdmin->moveup($id, $delta);
	}
	
	function update_parent(){
		$this->TreeAdmin->update_parent($this->data);
	}

	function index(){
		$this->_admin_index();

	}

	function add($parent_id = null){
		//Se definiu o pai
		if(!empty($parent_id)){
			$this->data['Jogo']['parent_id'] = $parent_id;
			//Salva automaticamente
			$_SERVER['REQUEST_METHOD'] = 'post';
		}
		$this->_admin_add();
		
	}

	function edit($id = null){
 		$this->_admin_edit($id);
	}

	function delete($id = null){
		$this->_admin_delete($id);
	}

}