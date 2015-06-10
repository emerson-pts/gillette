<?php
define('GALERIA_ARQUIVO_FILE_DIR', SITE_DIR.'webroot/img/upload/galerias');

class GaleriaArquivo extends AppModel {

	var $name = 'GaleriaArquivo';
	var $displayField = 'titulo';
 	var $order = 'GaleriaArquivo.order ASC';
 	
	var $actsAs = array(
		'Sortable' => array('group' => 'galeria_id',), 
        'MeioUpload' => array('arquivo' => array(
			'dir' => GALERIA_ARQUIVO_FILE_DIR,
			'url' => 'upload/galerias',
        ),)
    );

	var $belongsTo = array(
		'Galeria' => array(
			'className' => 'Galeria',
			'foreignKey' => 'galeria_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

	var $validate = array(
/*		'arquivo' => array(
			'Empty' => array(
				'check' => false,
			),
			'validateAtLeastOne' => array(
				'rule'	=> array('validateAtLeastOne', 'embed'),
				'message' => 'Você não enviou nenhum arquivo ou código de embed',
			
			),
		),
		'embed'	=> array(
			'validateAtLeastOne' => array(
				'rule'	=> array('validateAtLeastOne', 'arquivo'),
				'message' => 'Você não enviou nenhum arquivo ou código de embed',
			),
		),
*/
/*		
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
*/
	);
	
	function beforeValidate(){
		//Limpa array do arquivo, caso ele não tenha sido enviado
		//Caso contrário a validação validateAtLeastOne, falhará
		/*if(
			//Se não enviou o arquivo
			$this->data[$this->alias]['arquivo']['error'] == UPLOAD_ERR_NO_FILE
			&& 
			(empty($this->data[$this->alias]['id']) //novo registro
			||
			!array_key_exists('remove', $this->data[$this->alias]['arquivo']) //não existe campo para remover arquivo... então nenhum arquivo foi enviado
			||
			!empty($this->data[$this->alias]['arquivo']['remove'])
			)
		){
			//Se não é novo registro e solicitou a remoção altera a mensagem de erro
			if(
				!empty($this->data[$this->alias]['id']) //novo registro
				&& !empty($this->data[$this->alias]['arquivo']['remove'])
			){
				$this->validate['arquivo']['validateAtLeastOne']['message'] = 'Você solicitou a remoção do arquivo, mas não enviou um novo ou enviou um código de embed.';
			}
			$this->data[$this->alias]['arquivo'] = null;

		}*/
		
		//gera url amigável
		$this->friendly_url_validate(array('titulo' => (!array_key_exists('titulo', $this->data[$this->alias]) ? '' : $this->data[$this->alias]['titulo'])));

		return true;
	}
	
	function afterSave($created){
		parent::afterSave($created);
		
		//Se é um registro novo, coloca em 1.o
		if($created){
			$this->moveTop($this->id);
		}
		return true;
	}

	function setupAdmin($action = null, $id = null){

		//Lê dados do usuário e armazena na variável usuario do modelo
		App::import('Core', 'CakeSession'); 
        $session = new CakeSession(); 
		if(!$session->started())$session->start();
		$this->usuario = $session->read('Auth.Usuario');

		$this->options['galeria_id'] = $this->Galeria->generatetreelist($conditions=null, $keyPath=null, $valuePath=null, $spacer= '_', $recursive=null);
		
		$setupAdmin = array(
			'displayField' => $this->displayField,
			
			'searchFields' => array(
				'Galeria.label',
				'GaleriaArquio.id',
				'GaleriaArquivo.data',
				'GaleriaArquivo.titulo',
			),

			'pageDescriptionIndex' => __('Arraste os itens para ordená-los. Para acessar o menu de opções, passe o mouse sobre eles.', true),
			
			'topLink' => array(
				'Novo arquivo' => array('url' => array('action' => 'add'), 'include_params_in_url' => true, 'htmlAttributes' => array()),
				'Envio Múltiplo' => array('url' => array('action' => 'add', 'multiple'), 'include_params_in_url' => true, 'htmlAttributes' => array()),
				'Álbuns' => array('url' => array('controller' => 'galerias', 'action' => 'index',), 'htmlAttributes' => array()),
			),
			
			'listFields' => array(
				'GaleriaArquivo.id' => array('table_head_cell_param' => 'class="text-align-center" width="70"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Código'),
				'Galeria.label'	 => array('table_head_cell_param' => 'class="text-align-left" width="100"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Galeria', ),
				'GaleriaArquivo.titulo' => array('table_head_cell_param' => 'class="text-align-left"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Título'),
				'GaleriaArquivo.data' => array('table_head_cell_param' => 'class="text-align-left" width="100"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Data', ),
			),
			

			'defaultLimit' => 999999,
			
//			'showLog' => array('index', 'edit'),
			
			'containIndex' => array('Galeria',),
			'containAddEdit' => array('Galeria', ),
			
	/*		'box_order' => array(
				'Noticia.id' => 'Código',
				'Noticia.titulo' => 'Título',
			),
	*/
			'add_title' => 'Novo Arquivo',

/*
			'box_filter' => array(
				'Noticia.tipo' => array('title' => 'Filtrar tipo', 'options' => array('*' => 'Todos',) + $this->tipos,),
				'Noticia.status' => array('title' => 'Filtrar status', 'options' => array('*' => 'Todos',) + $status,),
				'Noticia.usuario_id' => array('title' => 'Filtrar autor', 'options' => array('*' => 'Todos',) + $this->Usuario->find('list', array('order' => 'apelido'))),
			),
*/

			'allowFilter'	=> array(
				$this->alias.'.galeria_id' => array(),
			),
			
			'formParams' => array('enctype' => 'multipart/form-data'),
			
			'form'	=> array(
				'galeria_id'	=> array('label' => 'Galeria', 'options' => $this->options['galeria_id'],),
				'titulo'		=> array('label' => 'Título', 'type' => 'text', 'limit' => 255),
				'friendly_url'	=> array('label' => 'Url Amigável', 'type' => 'text', 'limit' => 255, 'after' => '&nbsp;<small>Deixe em branco, caso queira defini-la automaticamente.</small>',),
				'legenda'		=> array('label' => 'Legenda','type' => 'textarea', 'cols' => 50, 'rows' => 6, 'limit' => 255),
				'data'			=> array('label' => 'Data', 'class' => 'datepicker dateMask', 'type' => 'text', ),
				'arquivo'		=> array('label' => 'Arquivo', 'type' => 'file', 'multiple' => true, 'div' => 'input required', 'show_remove' => true, 'show_preview' => '640x480', 'show_preview_thumb' => array('size=120*90', 'crop=120x90'), 'show_preview_url' => '/../'.str_replace('.admin', '', APP_DIR).'/thumbs/'),
//				'embed'			=> array('label' => 'Código de Embed <br />ou Link externo', 'after' => ' <small>Ex.: Youtube, link externo</small>', 'type' => 'textarea', 'cols' => 50, 'rows' => 6, 'limit' => 255),
			),
		);
		
		return $setupAdmin;
	}
	
}