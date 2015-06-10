<?php
define('GALERIA_FILE_DIR', SITE_DIR.'webroot/img/upload/galerias');

class Galeria extends AppModel {
    var $name = 'Galeria';
	var $displayField = 'label';
    var $actsAs = array(
		'TreePlus',
        'MeioUpload' => array('imagem_capa' => array(
			'dir' => GALERIA_FILE_DIR,
			'url' => 'upload/galerias',
        ),)
    );
    var $order = 'Galeria.lft ASC';
    var $hasMany=array(
    	'GaleriaArquivo'=>array(
    		'className' => 'GaleriaArquivo',
			'foreignKey'=> 'galeria_id',
			'dependent'	=> true,
			'order' 	=> array('GaleriaArquivo.order ASC'),
    	),
    	'Subgaleria' => array(
    		'className' => 'Galeria',
			'foreignKey'=> 'parent_id',
    	),
    );
	
    var $validate = array(
		'imagem_capa' => array(
			'Empty' => array(
				'check' => false,
			),
		),

		'label' => array(
			'notEmpty' => array(
			   'rule'=>'notEmpty', 
				'required'=>true,  
				'allowEmpty'=>false,
				'message'=>'Por favor, digite o rótulo.', 
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

		//Lê dados do usuário e armazena na variável usuario do modelo
		App::import('Core', 'CakeSession'); 
        $session = new CakeSession(); 
		if(!$session->started())$session->start();
		$this->usuario = $session->read('Auth.Usuario');
		        
  		//Monta opções de Referência
        if(!empty($id))$current_path = $this->getfullpath($id);
		$this->options['parent_id'] = $this->generatetreelist($conditions=null, $keyPath=null, $valuePath=null, $spacer= '_', $recursive=null);
		foreach($this->options['parent_id'] AS $key=>$value){
            $path = $this->getfullpath($key);
            
            if(empty($current_path) || !preg_match('/^'.preg_quote($current_path, '/').'/', $path))
                $this->options['parent_id'][$key] = $path;
            else
                unset($this->options['parent_id'][$key]);
		}

		//Seta flag para automaticamente incluir o full path no find
		$this->afterFindGetfullpath = array('id', $this->displayField,);

        $setupAdmin = array(
			'displayField' => $this->displayField,

            'topLink' => array(
				'Novo Álbum' => array('url' => array('action' => 'add'), 'htmlAttributes' => array()),
			),

			'pageDescriptionIndex' => __('Arraste os itens para reposicioná-los. Para acessar o menu de opções ou enviar arquivos, passe o mouse sobre eles.', true),

			'listFields' => array(
				'Galeria.label' => array('table_head_cell_param' => 'class="text-align-left"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Álbum', ),
				'Galeria.data' => array('table_head_cell_param' => 'class="text-align-left" width="70"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Data', ),
			),
			
			'defaultLimit' => 999999,

			'formParams' => array('enctype' => 'multipart/form-data'),

			'form'	=> array(
				'parent_id'		=> array('label' => 'Referência', 'empty' => '--Raiz--', 'options' => $this->options['parent_id'],),
				'label'			=> array('label' => 'Álbum',),
				'friendly_url'	=> array('label' => 'Url Amigável', 'type' => 'text', 'after' => '&nbsp;<small>Deixe em branco, caso queira defini-la automaticamente.</small>',),
				'data'			=> array('label' => 'Data', 'class' => 'dateMask datepicker', 'type' => 'text','maxlength'=>'10'),
				'imagem_capa'	=> array('label' => 'Capa do álbum', 'type' => 'file','after'=>'<small>(preferencialmente imagem com 281 px de largura e 154 px de altura)</small>', 'show_remove' => true, 'show_preview' => '640x480', 'show_preview_url' => '/../'.str_replace('.admin', '', APP_DIR).'/thumbs/'),
				'descricao'		=> array('label' => 'Descrição', 'div' => 'ckeditor', 'class' => 'ckeditor', 'cols' => 50, 'rows' => 15, ),
			),
			'listActions' => array(
				'admin/icon-view.gif' => array(
					'url' => array('controller' => 'galeria_arquivos', 'action' => 'index', 'params' => 'filter[GaleriaArquivo.galeria_id]:{/'.$this->alias.'/id}'),
					'params' => array(
						'title' => __('Fotos', true), 
						//'class' => 'picto view',
						'escape' => false,
					),
				),
			),
		);
		
		return $setupAdmin;
	}    
}