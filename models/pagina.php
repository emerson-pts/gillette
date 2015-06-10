<?php
define('PAGINA_IMAGE_DIR', SITE_DIR.'webroot/img/upload/paginas');

class Pagina extends AppModel {

	var $name = 'Pagina';
	var $displayField = 'titulo';
	
	var $actsAs = array(
        'MeioUpload' => array('image' => array(
			'dir' => PAGINA_IMAGE_DIR,
			'url' => 'upload/paginas',
        ),)
    );

	var $validate = array(
		/*'image' => array(
			'Empty' => array(
				'check' => false,
			),
		),*/
		
		'titulo' => array(
			'MinLength' => array(
				'rule'=>array('minLength',1),
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

	function setupAdmin($action = null, $id = null){

		$align 	= array('left' => 'Esquerda', 'center' => 'Centro', 'right' => 'Direita',);
		
		$setupAdmin = array(
			'displayField' => $this->displayField,
			
//			'pageTitle'				=> __('Páginas', true),
//			'pageDescriptionIndex'	=> __('Modelo de conteúdo com texto e imagens', true),
			
			'searchFields' => array(
				$this->alias.'.id',
				$this->alias.'.created',
				$this->alias.'.titulo',
				$this->alias.'.conteudo',
			),
			
			'topLink' => array(
				'Nova página' => array('url' => array('controller' => 'paginas', 'action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'listFields' => array(
				$this->alias.'.id' => array('table_head_cell_param' => 'class="text-align-center" width="70"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Código'),
				$this->alias.'.titulo' => array('table_head_cell_param' => 'class="text-align-left" width="300"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Título'),
				$this->alias.'.friendly_url' => array('table_head_cell_param' => 'class="text-align-left"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Url amigável'),
			),
			
			'defaultOrder' => array($this->alias.'.titulo' => 'ASC',),
			'defaultLimit' => 25,
			
//			'showLog' => array('index', 'edit'),
			
			'containIndex' => array(),
			'containAddEdit' => array(),
			
/*			'box_order' => array(
				$this->alias.'.id' => 'Código',
				$this->alias.'.titulo' => 'Título',
			),
*/
/*			'box_filter' => array(
				$this->alias.'.status' => array('title' => 'Filtrar status', 'options' => array('*' => 'Todos', 'x'=> 'Ativo' ) ,),
			),
*/

			'formParams' => array('enctype' => 'multipart/form-data'),
			
			'form'	=> array(
				'titulo'		=> array('label' => 'Título', 'type' => 'text'), //textarea', 'cols' => 50, 'rows' => 6, 'limit' => 255),
				'friendly_url'	=> array('label' => 'Url Amigável', 'type' => 'text', 'after' => '&nbsp;<small>Deixe em branco, caso queira defini-la automaticamente.</small>',),
				'image'			=> array('label' => 'Imagem', 'type' => 'file', 'show_remove' => true, 'show_preview' => '640x480', 'show_preview_url' => '/../'.str_replace('.admin', '', APP_DIR).'/thumbs/'),
				'image_align'	=> array('label' => 'Alinhamento', 'type'=> 'select', 'default' => key($align), 'options' => $align),
				'image_legenda'	=> array('label' => 'Legenda',),
				'texto_aspas'	=> array('label' => 'Texto aspas', 'cols' => 70, 'rows' => 2, 'limit' => 255),
				'conteudo'		=> array('label' => 'Conteúdo', 'div' => 'ckeditor', 'class' => 'ckeditor', 'cols' => 50, 'rows' => 15,  'before' => '<small class="right">&nbsp;Inclua a marcação [texto_aspas] para determinar a posição do "texto aspas".<br />Para dividir o conteúdo em diversas páginas, digite [pagebreak].</small>',),
			),
		);
		
		return $setupAdmin;
	}
}