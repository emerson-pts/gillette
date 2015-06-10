<?php
class Configuration extends AppModel {

	var $name = 'Configuration';
	var $displayField = 'description';
    var $order = 'Configuration.config ASC';
    
	var $validate = array(
		'config' => array(
			'notEmpty'	=> array(
				'rule' => 'notEmpty',
				'message'=> 'O nome da configuração não foi informado.',
			),
			'isUnique'	=> array(
				'rule' => 'isUnique',
				'message'=> 'Esta configuração já está cadastrada.',
			),
			
		),
	);

	function load($config = null){
		$params = array(
			'fields' => array('config', 'value'),
		);
		if($config){
			if(preg_match('/\*$/', $config))
				$params['conditions'] = array('config LIKE' => preg_replace('/\*$/', '%', $config));
			else
				$params['conditions'] = array('config' => $config);
		}
		
		$config_data = array();
		
		if($result = $this->find('all', $params)){
			foreach($result AS $r){
				$config_data[$r[$this->alias]['config']] = $r[$this->alias]['value'];
			}
		}
		Configure::write($config_data);
	}
	
	
	function setupAdmin($action = null, $id = null){				
		$setupAdmin = array(
			'displayField' => $this->displayField,
			
			'searchFields' => array(
//				$this->alias.'.id',
				$this->alias.'.config',
				$this->alias.'.value',
				$this->alias.'.description',
			),
		
			'topLink' => array(
				'Nova Configuração' => array('url' => array('controller' => 'configurations', 'action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'listFields' => array(
				$this->alias.'.config' => array('table_head_cell_param' => 'class="text-align-left" width="70"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => __('Configuração', true)),
				$this->alias.'.description' => array('table_head_cell_param' => 'class="text-align-left" width="230"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => __('Descrição', true)),
				$this->alias.'.value' => array('table_head_cell_param' => 'class="text-align-left" width="400"', 'table_body_cell_param' => 'class="text-align-left configuration_value"', 'label' => __('Valor', true)),
			),
			
			'defaultOrder' => array($this->alias.'.config' => 'ASC',),

			'showLog' => array('index', 'edit'),
						
			'containIndex' => false,
			'containAddEdit' => false,

			'listActions' => array(
				'Editar' => array(
					'url' => array('action' => 'edit_parcial', 'params' => array('/'.$this->alias.'/id')),
					'params' => array('class' => 'table-partial-edit-link',),
				),
			),

		);
		
		
		if($action == 'edit_parcial'){
			$setupAdmin['form'] = array(
				'value'			=> array('label' => 'Valor', 'type' => 'textarea', 'cols' => 90, 'rows' => 10, 'limit' => 65535,),
			);
			$setupAdmin['save_params'] = array('fieldList' => array('value'));
		}else{
			$setupAdmin['form'] = array(
				'config'		=> array('label' => 'Configuração',),
				'description'	=> array('label' => 'Descrição', 'type' => 'textarea', 'cols' => 90, 'rows' => 2, 'limit' => 255),
				'value'			=> array('label' => 'Valor', 'type' => 'textarea', 'cols' => 90, 'rows' => 10, 'limit' => 65535),
			);
		}
		
		return $setupAdmin;
	}
}