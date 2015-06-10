<?php
class Jogo extends AppModel {
    var $name = 'Jogo';
	var $displayField = 'titulo';

    var $actsAs = array('TreePlus');
    var $order = 'Jogo.lft ASC';
    
	var $belongsTo = array(
		
		'Equipe1Jogador1' => array(
			'className' => 'Jogador',
			'foreignKey' => 'equipe1_jogador1_id',
		),
		
		'Equipe1Jogador2' => array(
			'className' => 'Jogador',
			'foreignKey' => 'equipe1_jogador2_id',
		),
		
		'Equipe2Jogador1' => array(
			'className' => 'Jogador',
			'foreignKey' => 'equipe2_jogador1_id',
		),
		
		'Equipe2Jogador2' => array(
			'className' => 'Jogador',
			'foreignKey' => 'equipe2_jogador2_id',
		),
		
	);
	
	var $validate = array(
		'titulo' => array(
			'notEmpty' => array(
				'rule'=> 'notEmpty',
				'required'=>true,
				'allowEmpty'=>false,
				'message'=>'O título está em branco.',
			),
			'friendly_url' => array(
				'rule' => 'friendly_url_validate',
				'required'=>false,
				'allowEmpty'=>true,
				'message'=> 'Não foi possível gerar uma url amigável.',
			),
		),

		'friendly_url' => array(
			'rule' => 'isUnique',
			'message'=> 'A URL amigável deve ser única.',
		),	
	);

	function chaves($id = null){	
		$chaves = $this->find('all', array('conditions' => array('parent_id' => $id),));
		foreach($chaves AS $key=>$value){
			$chaves[$key]['chaves'] = $this->chaves($value[$this->alias]['id']);
		}
		return $chaves;
	}
	
	function setupAdmin($action = null, $id = null){
  		//Monta opções de Referência
        if(!empty($id))$current_path = $this->getfullpath($id);
		   
/*        $this->options = array(
			'parent_id' => $this->generatetreelist($conditions=null, $keyPath=null, $valuePath=null, $spacer= '_', $recursive=null),
		);
		
		foreach($this->options['parent_id'] AS $key=>$value){
            $path = $this->getfullpath($key);
            
            if(empty($current_path) || !preg_match('/^'.preg_quote($current_path, '/').'/', $path))
                $this->options['parent_id'][$key] = $path;
            else
                unset($this->options['parent_id'][$key]);
		}
*/

		$this->options['jogador_id'] = $this->Equipe1Jogador1->find('list');


		//Seta flag para automaticamente incluir o full path no find
		$this->afterFindGetfullpath = array('id', 'titulo');

        $setupAdmin = array(
			'displayField' => $this->displayField,
			'displayFieldTreeIndex' => $this->displayField,
			'defaultOrder' => $this->order,
			
			'pageDescriptionIndex' => __('Para acessar o menu de opções, passe o mouse sobre eles.', true),
			
            'topLink' => array(
				'Nova Chave Principal' => array('url' => array('controller' => 'jogos', 'action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'defaultLimit' => 999999,

			'containIndex' => array('Equipe1Jogador1', 'Equipe1Jogador2', 'Equipe2Jogador1', 'Equipe2Jogador2',),
			'containAddEdit' => array(),
			
			'allowFilter'	=> array(
				$this->alias.'.parent_id' => array(),
			),
			
			'form'	=> array(
				'parent_id'	=> array('type' => 'hidden'),//label' => 'Referência', 'empty' => '--Raiz--',*/ 'options' => $this->options['parent_id'],),
				'titulo'	=> array('label' => 'Título da chave',),
				'friendly_url'	=> array('label' => 'Url Amigável', 'type' => 'text', 'after' => '<br /><small>Deixe em branco, caso queira defini-la automaticamente.</small>',),
				'fases'		=> array('label' => 'Fases', 'style' => 'width: 100%;', 'after' => '<br /><small>Digite o nome de cada fase separada por vírgula, começando pela inicial até a final.<br />Ex.: Campeão, Final, Semifinal, Quartas-de-final, Segunda Rodada, Primeira Rodada</small>'  ),
				'qtd_jogadores_equipe'	=> array('label' => 'Quantidade de jogadores por equipe', 'maxlength' => 2, 'default' => 1, 'div' => 'inline-mini-label', 'options' => array(1 => 1, 2 => 2), 'default' => 1, 'class' => 'onlyNumber',),
//				'data'		=> array('label' => 'Data', 'type' => 'text', 'class' => 'dateMask datepicker',),
				'equipe1_jogador1_id' => array('label' => 'Equipe 1 - Jogador 1', 'type' => 'select', 'empty' => '--selecione--', 'options' => $this->options['jogador_id'], 'style' => 'width: 300px',),
//				'equipe1_jogador1_text' => array('label' => 'Equipe 1 - Jogador 1 (observação)',),
				'equipe1_jogador2_id' => array('label' => 'Equipe 1 - Jogador 2', 'type' => 'select', 'empty' => '--selecione--', 'options' => $this->options['jogador_id'], 'style' => 'width: 300px',),
//				'equipe1_jogador2_text' => array('label' => 'Equipe 1 - Jogador 2 (observação)',),
				'equipe1_placar'	=> array('label' => 'Equipe 1 - Placar', 'cols' => 70, 'rows' => 2, 'limit' => 255),
				'equipe2_jogador1_id' => array('label' => 'Equipe 2 - Jogador 1', 'type' => 'select', 'empty' => '--selecione--', 'options' => $this->options['jogador_id'], 'style' => 'width: 300px',),
//				'equipe2_jogador1_text' => array('label' => 'Equipe 2 - Jogador 1 (observação)',),
				'equipe2_jogador2_id' => array('label' => 'Equipe 2 - Jogador 2', 'type' => 'select', 'empty' => '--selecione--', 'options' => $this->options['jogador_id'], 'style' => 'width: 300px',),
//				'equipe2_jogador2_text' => array('label' => 'Equipe 2 - Jogador 2 (observação)',),
				'equipe2_placar'	=> array('label' => 'Equipe 2 - Placar', 'cols' => 70, 'rows' => 2, 'limit' => 255),
			),
		);
		
		return $setupAdmin;
	}  
	
	function beforeValidate(){
		//Limpa fases
		if(!empty($this->data[$this->alias]['fases'])){
			$this->data[$this->alias]['fases'] = preg_replace('/(^,+|,+$)/', '', trim(preg_replace('/ *, */', ',', $this->data[$this->alias]['fases'])));
		}
		
		//Se é a chave principal
		if(empty($this->data[$this->alias]['parent_id'])){
			$this->data[$this->alias]['equipe1_jogador1_id'] = 
			$this->data[$this->alias]['equipe1_jogador1_text'] = 
			$this->data[$this->alias]['equipe1_jogador2_id'] =
			$this->data[$this->alias]['equipe1_jogador2_text'] =
			$this->data[$this->alias]['equipe1_placar'] =
			$this->data[$this->alias]['equipe2_jogador1_id'] = 
			$this->data[$this->alias]['equipe2_jogador1_text'] = 
			$this->data[$this->alias]['equipe2_jogador2_id'] =
			$this->data[$this->alias]['equipe2_jogador2_text'] =
			$this->data[$this->alias]['equipe2_placar'] =
				''
			;
		}
		//Caso contrário
		else{
			$this->data[$this->alias]['friendly_url'] = 
			$this->data[$this->alias]['fases'] =
			$this->data[$this->alias]['qtd_jogadores_equipe'] = 
				''
			;

			//Recupera árvore do jogos
			if(!$jogos = $this->getpath($this->data[$this->alias]['parent_id'], array('parent_id', 'id', 'titulo', 'qtd_jogadores_equipe', 'fases'))){
				$this->invalidate("parent_id", 'Não foi possível recuperar os dados da chave principal. Você não pode criar uma chave a partir desta área.');
				return false;
			}

			$fases = explode(',', $jogos[0][$this->alias]['fases']);	
			$count_fase = count($jogos) - 1;
			//Seta o nome da chave com base nas fases definidas no primeiro nível
			if(!isset($fases[$count_fase])){
				$this->invalidate("parent_id", 'Não foi possível identificar o nível desta etapa. Por favor, verifique a configuração da chave.');
				return false;
			}else{
				$this->data[$this->alias]['titulo'] = $fases[$count_fase];
			}

			if($jogos[0][$this->alias]['qtd_jogadores_equipe'] == 1){
				$this->data[$this->alias]['equipe1_jogador2_id'] = 
				$this->data[$this->alias]['equipe1_jogador2_text'] = 
				$this->data[$this->alias]['equipe2_jogador2_id'] = 
				$this->data[$this->alias]['equipe2_jogador2_text'] = '';
			}
		}
		return true;
	}
}