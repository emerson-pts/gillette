<?php
define('TIMELINE_IMAGE_DIR', SITE_DIR.'webroot/img/upload/timelines');

class Timeline extends AppModel {

	var $name = 'Timeline';
	var $displayField = 'ano';
	
	var $actsAs = array(
        'MeioUpload' => array('image' => array(
			'dir' => TIMELINE_IMAGE_DIR,
			'url' => 'upload/timelines',
        ),)
    );

	var $validate = array(
		'image' => array(
			'Empty' => array(
				'check' => false,
			),
		),

		'ano' => array(
			'numeric' => array(
				'rule'		=>'numeric',
				'message'	=>'O ano somente aceita números.',
			),
			'notEmpty' => array(
				'rule'		=> 'notEmpty',
				'required' 	=> true,
				'allowEmpty'=> false,
				'message'	=> 'O ano está em branco.',
			),
		),
		
		'titulo' => array(
			'notEmpty' => array(
				'rule'=>'notEmpty',
				'required'=>true,
				'allowEmpty'=>false,
				'message'=>'O título está em branco.',
			),
		),
	);

	function setupAdmin($action = null, $id = null){
		
		$setupAdmin = array(
			'displayField' => $this->displayField,
			
			'pageTitle'				=> __('História', true),
//			'pageDescriptionIndex'	=> __('Modelo de conteúdo com texto e imagens', true),
			
			'searchFields' => array(
				$this->alias.'.id',
				$this->alias.'.created',
				$this->alias.'.ano',
				$this->alias.'.titulo',
				$this->alias.'.conteudo',
			),
			
			'topLink' => array(
				'Novo ano' => array('url' => array('controller' => 'timelines', 'action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'listFields' => array(
//				$this->alias.'.id' => array('table_head_cell_param' => 'class="text-align-center" width="70"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Código'),
				$this->alias.'.ano' => array('table_head_cell_param' => 'class="text-align-center" width="70"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Ano'),
				$this->alias.'.titulo' => array('table_head_cell_param' => 'class="text-align-left"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Título'),
			),
			
			'defaultOrder' => array($this->alias.'.ano' => 'ASC',),
			'defaultLimit' => 25,
			
//			'showLog' => array('index', 'edit'),
			
			'containIndex' => array(),
			'containAddEdit' => array(),
			
			'box_order' => array(
				$this->alias.'.ano' => 'Ano',
				$this->alias.'.titulo' => 'Título',
			),

/*			'box_filter' => array(
				$this->alias.'.status' => array('title' => 'Filtrar status', 'options' => array('*' => 'Todos', 'x'=> 'Ativo' ) ,),
			),
*/

			'formParams' => array('enctype' => 'multipart/form-data'),
			
			'form'	=> array(
				'ano'			=> array('label' => 'Ano', 'type' => 'text', 'maxlength' => '4', 'class' => 'onlyNumber',),
				'titulo'		=> array('label' => 'Título', 'type' => 'text', 'limit' => 50), //textarea', 'cols' => 50, 'rows' => 6, 'limit' => 255),
				'image'			=> array('label' => 'Imagem', 'type' => 'file', 'show_remove' => true, 'show_preview' => '640x480', 'show_preview_url' => '/../'.str_replace('.admin', '', APP_DIR).'/thumbs/'),
				'conteudo'		=> array('label' => 'Conteúdo', 'div' => 'ckeditor', 'class' => 'ckeditor', 'cols' => 50, 'rows' => 15,  'before' => '<small class="right">&nbsp;Inclua a marcação [texto_aspas] para determinar a posição do "texto aspas".<br />Para dividir o conteúdo em diversas páginas, digite [pagebreak].</small>',),
			),
		);
		
		return $setupAdmin;
	}
}