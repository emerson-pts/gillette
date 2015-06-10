<?php
class Grupo extends AppModel {
	var $name = 'Grupo';
	var $displayField = 'nome';
	var $actsAs = array('Acl' => array('requester'));
	
	var $hasMany = array(
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'grupo_id',
			'conditions' => '',
			'fields' => array('id','email','nome','status'),
			'order' => 'Usuario.nome'
		)
	);


	var $validate = array(			
		'nome' => array(
			'rule'=>array('minLength',4), 
			'required'=>true,  
			'allowEmpty'=>false,
			'message'=>'Por favor, digite o nome do grupo.', 
		),
		
	);

	function parentNode(){
		return null;
	}

	function setupAdmin($action = null, $id = null){
		
		$setupAdmin = array(
			'displayField' => $this->displayField,
			
			'searchFields' => array(
				'Grupo.id',
				'Grupo.nome',
			),
			
			'topLink' => array(
				'Novo grupo' => array('url' => array('controller' => 'grupos', 'action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'listFields' => array(
				'Grupo.id' => array('table_head_cell_param' => 'class="text-align-center" width="100"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Código'),
				'Grupo.nome' => 'Grupo',
			),	
			
			'form'	=> array(
				'nome'		=> array(),
			),

			'listActions' => array(
				'<span>'.__('Permissões', true).'</span>' => array(
					'url' => array('controller' => 'acl', 'action' => 'index', 'params' => 'Grupo/{/'.$this->alias.'/id}'),
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

