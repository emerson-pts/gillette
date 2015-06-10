<?php
define('VITRINE_IMAGE_DIR', SITE_DIR.'webroot/img/upload/vitrines');

class Vitrine extends AppModel {

	var $name = 'Vitrine';
	var $displayField = 'titulo';
    var $order = 'Vitrine.peso DESC';

	var $actsAs = array(
        'MeioUpload' => array(
			'imagem' => array(
				'dir' => VITRINE_IMAGE_DIR,
				'url' => 'upload/vitrines',
				'length' => array(
					'minWidth' => 960,
					'maxWidth' => 960,
					'minHeight' => 348,
					'maxHeight' => 348,
					'aspectRatio' => 0,
				),
			),
		),
		
	);
	
	
	var $validate = array(
		
	);

	public function __construct($id=false,$table=null,$ds=null){
		parent::__construct($id,$table,$ds);
		$this->virtualFields = array(
			'ativo' => "IF(
				(ISNULL(`{$this->alias}`.`data_inicio`) AND ISNULL(`{$this->alias}`.`data_fim`))
				OR (ISNULL(`{$this->alias}`.`data_inicio`) AND `{$this->alias}`.`data_fim` >= DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'))
				OR (DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i') BETWEEN `{$this->alias}`.`data_inicio` AND `{$this->alias}`.`data_fim`)
				OR (ISNULL(`{$this->alias}`.`data_fim`) AND `{$this->alias}`.`data_inicio` <= DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'))
				, 1, 0)",
		);
	}

	function setupAdmin($action = null, $id = null){

		//Lê dados do usuário e armazena na variável usuario do modelo
		App::import('Core', 'CakeSession'); 
        $session = new CakeSession(); 
		if(!$session->started())$session->start();
		$this->usuario = $session->read('Auth.Usuario');
		
		$setupAdmin = array(
			'displayField' => $this->displayField,
			
			'searchFields' => array(
				$this->alias.'.id',
				$this->alias.'.titulo',
				$this->alias.'.subtitulo',
			),
			
			'topLink' => array(
				'Nova Vitrine' => array('url' => array('action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'defaultOrder' => array($this->alias.'.peso' => 'DESC', $this->alias.'.id' => 'DESC',),
						
			'containIndex' => false,
			'containAddEdit' => false,

			'formParams' => array('enctype' => 'multipart/form-data'),

			'form'	=> array(
				'peso'			=> array('label' => 'Prioridade', 'type' => 'text', 'maxlength' => 2, 'size' => 5, 'after' => ' (<small>Utilizado para definir a prioridade de exibição</small>)', 'class' => 'txtbox-auto onlyNumber',),
				'titulo'		=> array('label' => 'Título', 'type' => 'text','maxlength' => 255, 'limit' => 11),
				'chamada'		=> array('label' => 'Chamada', 'type' => 'textarea', 'cols' => 50, 'rows' => 4, 'limit' => 95),
				'url'			=> array('label' => 'Url',),
				'imagem'		=> array('label' => 'Imagem', 'type' => 'file', 'after' => '<small></small>', 'show_remove' => true, 'show_preview' => '609x270', 'show_preview_url' => '/../'.str_replace('.admin', '', APP_DIR).'/thumbs/','after' => ' (<small>Tamanho imagem 960x348</small>)'), 
				'data_inicio' 	=> array('label' => 'Data de início', 'type' => 'text', 'class' => 'dateMaskDiaHora',),
				'data_fim' 		=> array('label' => 'Data de término', 'type' => 'text', 'class' => 'dateMaskDiaHora',),
			),
		);
		
		return $setupAdmin;
	}

}