<?php
class FormInformativo extends AppModel {

	var $name = 'FormInformativo';
	var $useTable = false;
	
	
	var $validate = array(
		'nome' => array(
			'rule' => 'notEmpty', 
			'required'	=> true,
			'allowEmpty'=>false,
			'message' => "Campo Obrigatório",
		),
		'email' => array(
			'rule' => 'email', 
			'required'	=> true,
			'allowEmpty'=>false,
			'message' => "Email inválido",
		),
		
	);
	
	
	
	
	function setupAdmin($action = null, $id = null){

		//Lê dados do usuário e armazena na variável usuario do modelo
		App::import('Core', 'CakeSession'); 
        $session = new CakeSession(); 
		if(!$session->started())$session->start();
		$this->usuario = $session->read('Auth.Usuario');
		
		$setupAdmin = array(
			'displayField' => $this->displayField,
			
			'searchFields' => array(
				'FormInteresse.id',
				'FormInteresse.nome',
				'FormInteresse.email',
			),
				
			'topLink' => array(
				'Novo interesse' => array('url' => array('controller' => 'FormInteresses', 'action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'listFields' => array(
				'FormInteresse.id' => array('table_head_cell_param' => 'class="text-align-center" width="70"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Código'),
				'FormInteresse.nome' => array('table_head_cell_param' => 'class="text-align-left"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Nome'),
				'FormInteresse.email' => array('table_head_cell_param' => 'class="text-align-left" width="100"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Email', ),
				'FormInteresse.created' => array('table_head_cell_param' => 'class="text-align-left" width="80"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Data'),
			),
			
			'defaultOrder' => array($this->alias.'.created' => 'DESC',),
			'defaultLimit' => 100,
			
//			'showLog' => array('index', 'edit'),
			
			'containIndex' => array(),
			'containAddEdit' => array(),
			
			'box_order' => array(
				'FormInteresse.id' => 'Código',
				'FormInteresse.nome' => 'Nome',
				'FormInteresse.email' => 'Email',
			),
	
			/*'box_filter' => array(
				$this->alias.'.status' => array('title' => 'Filtrar status', 'options' => array('*' => 'Todos',) + $this->options['status'],),
				//$this->alias.'.categoria' => array('title' => 'Filtrar categoria', 'options' => array('*' => 'Todos',) + $this->options['categorias'],),
			),*/
	
			//'formParams' => array('enctype' => 'multipart/form-data'),
			
			'form'	=> array(
				'nome'				=> array('label' => 'Nome','type' => 'text','maxlength' => 100),
				'nome_responsavel'	=> array('label' => 'Nome do responsável','type' => 'text','maxlength' => 100),
				'email'				=> array('label' => 'Email','type' => 'text','maxlength' => 100),
				'nascimento'		=> array('label' => 'Nascimento','type' => 'text','maxlength' => 10),
				'rg'			=> array('label' => 'RG','type' => 'text','maxlength' => 30),
				'telefone'			=> array('label' => 'Telefone','type' => 'text','maxlength' => 20),
				'telefone2'			=> array('label' => 'Telefone2','type' => 'text','maxlength' => 20),
				'cursos'	=> array('label' => 'Cursos de interesse','cols' => 50, 'rows' => 15,),
			),
		);
		
		return $setupAdmin;
	}
	
}