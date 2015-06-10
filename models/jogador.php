<?php
define('JOGADOR_IMAGE_DIR', SITE_DIR.'webroot/img/upload/jogadores');

class Jogador extends AppModel {

	var $name = 'Jogador';
	var $displayField = 'nome';
	
	var $actsAs = array(
        'MeioUpload' => array('image' => array(
			'dir' => JOGADOR_IMAGE_DIR,
			'url' => 'upload/jogadores',
        ),)
    );

	var $options = array(
		'tipo' => array('principal' => 'Chave Principal', 'alternate' => 'Chave Alternates', 'convidado' => 'Convidados',),
		'destaque'	=> array('1' => 'Sim', '0' => 'Não'),
	);

	var $validate = array(
		'image' => array(
			'Empty' => array(
				'check' => false,
			),
		),
		
		'nome' => array(
			'notEmpty' => array(
				'rule'=>'notEmpty',
				'required'=>true,
				'allowEmpty'=>false,
				'message'=>'O nome está em branco.',
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

	function setupAdmin($action = null, $id = null){
		App::import('Vendor', 'WebjumpUtilities');
		$countries = Set::combine(WebjumpUtilities::countries(), '{s}.1', '{s}.0');
		$this->options['pais'] = $countries;
	
	
		$setupAdmin = array(
			'displayField' => $this->displayField,
			
//			'pageTitle'				=> __('Páginas', true),
//			'pageDescriptionIndex'	=> __('Modelo de conteúdo com texto e imagens', true),
			
			'searchFields' => array(
				$this->alias.'.id',
				$this->alias.'.created',
				$this->alias.'.nome',
				$this->alias.'.ranking',
				$this->alias.'.pais',
			),
			
			'topLink' => array(
				'Novo jogador' => array('url' => array('controller' => 'jogadores', 'action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'listFields' => array(
//				$this->alias.'.id' => array('table_head_cell_param' => 'class="text-align-center" width="70"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Código'),
				$this->alias.'.ranking' => array('table_head_cell_param' => 'class="text-align-center" width="80"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Ranking', 'field_printf' => '%s&ordm;'),
				$this->alias.'.pais_iso_2' => array('table_head_cell_param' => 'class="text-align-center" width="60"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'País', 'field_printf' => '<img src="'.preg_replace('/\.admin\/?$/', '', Router::url('/', true)).'/img/flags/%s.png" class="with-tip" title="%s" />'),
				$this->alias.'.nome' => array('table_head_cell_param' => 'class="text-align-left"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Nome'),
				$this->alias.'.destaque_formatado' => array('table_head_cell_param' => 'class="text-align-center" width="80"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Destaque'),
//				$this->alias.'.friendly_url' => array('table_head_cell_param' => 'class="text-align-left"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Url amigável'),
			),
			
			'defaultOrder' => array($this->alias.'.ranking' => 'ASC',),
			'defaultLimit' => 25,
			
//			'showLog' => array('index', 'edit'),
			
			'containIndex' => array(),
			'containAddEdit' => array(),
			
			'box_order' => array(
//				$this->alias.'.id' => 'Código',
				$this->alias.'.nome' => 'Nome',
				$this->alias.'.ranking' => 'Ranking',
			),
			'box_filter' => array(
				$this->alias.'.destaque' => array('title' => 'Filtrar Destaque', 'options' => array('*' => 'Todos',) + $this->options['destaque'] ,),
				$this->alias.'.tipo' => array('title' => 'Filtrar tipo', 'options' => array('*' => 'Todos',) + $this->options['tipo'] ,),
			),

			'formParams' => array('enctype' => 'multipart/form-data'),
			
			'form'	=> array(
				'nome'			=> array('label' => 'Nome', 'type' => 'text'), //textarea', 'cols' => 50, 'rows' => 6, 'limit' => 255),
				'friendly_url'	=> array('label' => 'Url Amigável', 'type' => 'text', 'after' => '&nbsp;<small>Deixe em branco, caso queira defini-la automaticamente.</small>',),
				'ranking'		=> array('label' => 'Ranking', 'type' => 'text', 'div' => 'input inline-mini-label', 'class' => 'onlyNumber', 'maxlength' => 4), //textarea', 'cols' => 50, 'rows' => 6, 'limit' => 255),
				'image'			=> array('label' => 'Imagem', 'type' => 'file', 'show_remove' => true, 'show_preview' => '640x480', 'show_preview_url' => '/../'.str_replace('.admin', '', APP_DIR).'/thumbs/'),
				'pais'			=> array('label' => 'País', 'type' => 'select', 'div' => 'input inline-mini-label', 'empty' => '--selecione--', 'options' => $this->options['pais']),
				'tipo'			=> array('label' => 'Tipo', 'type' => 'select', 'div' => 'input inline-mini-label', 'empty' => '--selecione--', 'options' => $this->options['tipo']),
				'cabeca_chave'	=> array('label' => 'Cabeça de chave - simples', 'type' => 'text', 'class'=>'', 'maxlength' => 2, 'div' => 'input inline-mini-label'),
				'cabeca_chave_dupla'	=> array('label' => 'Cabeça de chave - duplas', 'type' => 'text', 'class'=>'', 'maxlength' => 2, 'div' => 'input inline-mini-label'),
				'destaque'		=> array('label' => 'Destaque', 'type' => 'checkbox', 'class'=>'switch', 'default' => '0', 'value' => '1', ),
				'show_in_list'	=> array('label' => 'Exibir na lista', 'type' => 'checkbox', 'class'=>'switch', 'default' => '1', 'value' => '1', ),
				'ficha'			=> array('label' => 'Ficha',  'div' => 'ckeditor', 'class' => 'ckeditor', 'cols' => 50, 'rows' => 15,),
			),
		);
		
		return $setupAdmin;
	}
	
	function afterFindExecute($result){
		$result = parent::afterFindExecute($result);

		if(isset($result['pais'])){
			App::import('Vendor', 'WebjumpUtilities');
			$countries = Set::combine(WebjumpUtilities::countries(), '{s}.1', '{s}.1');
			$result['pais_iso_2'] = ($result['pais'] == '00' || empty($result['pais']) ? '00' : strtolower($countries[$result['pais']]));
		}

		if(isset($result['destaque'])){
			$result['destaque_formatado'] = $this->options['destaque'][$result['destaque']];
		}
		return $result;
	}
}