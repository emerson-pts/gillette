<?php
class Usuario extends AppModel {
	var $name = 'Usuario';
	var $displayField = 'apelido';
	var $actsAs = array('Acl' => array('requester'));
    var $order = 'Usuario.nome ASC';

	var $options = array('status' => array('1' => 'Ativo', '0' => 'Inativo'));
	
	var $belongsTo = array(
		'Grupo' => array(
			'className' => 'Grupo',
			'foreignKey' => 'Grupo_id',
			'conditions' => '',
			'fields' => array('id','nome'),
			'order' => ''
		)
	);

	var $validate = array(
		'email' => array(
			'email' => array(
				'rule'=>'email',
				'required'=>true,
				'allowEmpty'=>false,
				'message'=>	'E-mail inválido.',
			),
			'maxLength' => array(
				'rule' => array('maxLength', 50),
				'message' => 'O e-mail deve conter no máximo 50 caracteres.',
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'Este e-mail já está cadastrado.',
			),
		),
	
		'grupo_id' => array(
			'rule' => 'numeric',
			'required'=>true,
			'allowEmpty'=>false,
			'message'=>'Por favor, selecione um grupo.', 
		),
			
			
		'nome' => array(
			'rule'=>array('minLength',8), 
			'required'=>true,  
			'allowEmpty'=>false,
			'message'=>'Por favor, digite seu nome completo.', 
		),
		
		'senha' => array(
			'rule'=>'_validateSenha', 
			'required'=>true,  
			'allowEmpty'=>false,
			'message'=>'Por favor, digite uma senha com pelo menos 4 caracteres.', 
		),
		
		'status' => array(
			'rule'=>array('inList', array('0', '1')), 
			'required'=>true,  
			'allowEmpty'=>false,
			'message'=>'Por favor, informe o status.', 
		),
		
	);

	function parentNode(){
		if(!$this->id & empty($this->data)){
			return null;
		}
		if(empty($this->data)){
			$data = $this->read();
		}else{
			$data = $this->data;
		}
		if(empty($data['Usuario']['grupo_id'])){
			return null;
		}else{
			return array('Grupo' => array('id' => $data['Usuario']['grupo_id']));
		}
	}

	
	function afterFind($results){
		foreach($results AS $key=>$result){
			if (isset($result[$this->alias]['senha']))$results[$key][$this->alias]['senha'] = '';
			if (isset($result[$this->alias]['status']))$results[$key][$this->alias]['status_formatado'] = $this->options['status'][$results[$key][$this->alias]['status']];
			
		}
		return $results;
	}

	function afterSave($created){
		if(!$created){
			$parent = $this->parentNode();
			$parent = $this->node($parent);
			$node = $this->node();
			$aro = $node[0];
			$aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
			$this->Aro->save($aro);
		}
		return true;
	}
	
	function _validateSenha($senha){
		//se a senha está em branco
		if (Security::hash('', null, true) == $senha['senha']){
			if (is_numeric($this->data['Usuario']['id'])){
				//se está atualizando, então mantém a atual
				unset($this->data['Usuario']['senha']);
				return true;
			}else
				//se é novo, exige senha
				return false;
		}else
			return true;
	}

	function setupAdmin($action = null, $id = null){
	
		//Options de grupo
		$this->options['grupo'] = $this->Grupo->find('list');
	
		$setupAdmin = array(
			'displayField' => $this->displayField,
			
			'searchFields' => array(
				'Usuario.id',
				'Usuario.nome',
				'Usuario.apelido',
				'Usuario.email',
			),
			
			'topLink' => array(
				'Novo usuário' => array('url' => array('controller' => 'usuarios', 'action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'listFields' => array(
				'Usuario.id' => array('table_head_cell_param' => 'class="text-align-center" width="100"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Código'),
				'Usuario.nome' => 'Nome',
				'Usuario.email' => 'Email',
				'Usuario.apelido' => 'Apelido',
				'Grupo.nome' => 'Grupo',
				'Usuario.status_formatado' => 'Status',
			),
			
//			'contain' => false,
	
			'box_filter' => array(
				'Usuario.status' => array('title' => 'Filtrar status', 'options' => array('*' => 'Todos', '1' => 'Ativos', '0' => 'Inativos',))
			),
			
			'form'	=> array(
				'nome'		=> array(),
				'apelido'	=> array(),
				'email'		=> array('label' => 'E-mail'),
				'senha'		=> array('label' => 'Senha', 'type' => 'password', 'value' => '',),
				'grupo_id'	=> array('label' => 'Grupo', 'type' => 'select', 'empty' => '--Selecione--', 'options' => $this->options['grupo']),
				'status'	=> array('label' => 'Status', 'type' => 'select', 'options' => $this->options['status']),
			),

			'listActions' => array(
				'<span>'.__('Permissões', true).'</span>' => array(
					'url' => array('controller' => 'acl', 'action' => 'index', 'params' => 'Usuario/{/'.$this->alias.'/id}'),
					'params' => array(
						'title' => __('Permissões', true), 
						'class' => 'picto permission',
						'escape' => false,
					),
				),
			),
		);
		
		return $setupAdmin;
	}
}
