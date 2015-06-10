<?php
define('BOLETIM_IMAGE_DIR', SITE_DIR.'webroot/img/upload/boletim');

class Boletim extends AppModel {

	var $name = 'Boletim';
	var $displayField = 'titulo';
	var $useTable="boletim";
	
	var	$status = array('rascunho' => 'Rascunho', 'em_aprovação' => 'Em Aprovação', 'aprovada' => 'Aprovada',);
	

	
	var $actsAs = array(
        'MeioUpload' => array('image' => array(
			'dir' => BOLETIM_IMAGE_DIR,
			'url' => 'upload/boletim',
        ),)
    );

	

	var $validate = array(
		'image' => array(
			'Empty' => array(
				'check' => false,
			),
		),
		
		'titulo' => array(
			'MinLength' => array(
				'rule'=>array('minLength',3),
				'required'=>true,
				'allowEmpty'=>false,
				'message'=>'O título está em branco.',
			),
			'friendly_url' => array(
				'rule' => 'friendly_url_validate',
				'required'=>true,
				'allowEmpty'=>false,
				'message'=> 'Não foi possível gerar uma url amigável.',
			),
		),
			'conteudo' => array(
			'rule'=>'notEmpty',
			'required'=>true,
			'allowEmpty'=>false,
			'message'=> 'Informe o embed do issuu',
		)
	);

	function setupAdmin($action = null, $id = null){

		//Lê dados do usuário e armazena na variável usuario do modelo
		App::import('Core', 'CakeSession'); 
        $session = new CakeSession(); 
		if(!$session->started())$session->start();
		$this->usuario = $session->read('Auth.Usuario');

		$align 	= array('left' => 'Esquerda', 'center' => 'Centro', 'right' => 'Direita',);
		
		$setupAdmin = array(
			'displayField' => $this->displayField,
			
			'searchFields' => array(
				$this->alias.'.id',
				$this->alias.'.data',
				$this->alias.'.titulo',
			),
			
			'topLink' => array(
				'Novo Boletim' => array('url' => array('controller' => 'boletins', 'action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'listFields' => array(
//				'Boletim.id' => array('table_head_cell_param' => 'class="text-align-center" width="70"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Código'),

				'Boletim.data' => array('table_head_cell_param' => 'class="text-align-left" width="100"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Data', ),
				'Boletim.titulo' => array('table_head_cell_param' => 'class="text-align-left"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Título'),
				//'Boletim.tipo' => array('table_head_cell_param' => 'class="text-align-left" width="80"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Tipo'),
				//'Boletim.categoria_formatada' => array('table_head_cell_param' => 'class="text-align-left" width="80"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Categoria'),
				'Boletim.status_formatado' => array('table_head_cell_param' => 'class="text-align-left" width="80"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Status'),
			),
			
			'defaultOrder' => array($this->alias.'.id' => 'DESC',),
			
//			'showLog' => array('index', 'edit'),
			
//			'containIndex' => array('Usuario','Galeria'),
//			'containAddEdit' => array('BoletimRelacionada', ),
			
	/*		'box_order' => array(
				'Boletim.id' => 'Código',
				'Boletim.titulo' => 'Título',
			),
	*/		

			'box_filter' => array(
				'Boletim.status' => array('title' => 'Filtrar status', 'options' => array('*' => 'Todos',) + $this->status,),
			),
			
			'formParams' => array('enctype' => 'multipart/form-data'),
			
			'form'	=> array(
				'data'	=> array('label' => 'Data do boletim', 'class' => 'dateMask datepicker', 'type' => 'text', 'default' => date('d/m/Y'), ),
				'status'		=> array('label' => 'Status', 'type'=> 'select', 'default' => key($this->status), 'options' => $this->status),
				'titulo'		=> array('label' => 'Título', 'type' => 'text','maxlength' => 255),
				'image'			=> array('label' => 'Imagem', 'type' => 'file', 'show_remove' => true, 'show_preview' => '640x480', 'show_preview_url' => '/../'.str_replace('.admin', '', APP_DIR).'/thumbs/'),
				'conteudo'		=> array('label' => 'Embed', 'cols' => 50, 'rows' => 15, ),
			),
		);
		
		return $setupAdmin;
	}

	function afterFind($results){
		App::import('Helper', 'text');
		$text = new TextHelper;


		$tipos_url = array(
			'boletim' 		=> 'boletim',
		);

		foreach($results AS $key=>$r){
			if(!isset($r[$this->alias])){
				if(!empty($r['status']))
					$results[$key]['status_formatado'] = $this->status[$r['status']];
					

			}else{

				if(!empty($r[$this->alias]['status']))
					$results[$key][$this->alias]['status_formatado'] = $this->status[$r[$this->alias]['status']];
								
			}
			
			
		}
		
		return $results;
	}

	function beforeSave($created){
		parent::beforeSave($created);
		if(!empty($this->data[$this->alias]['categoria'])){
			$this->data[$this->alias]['categoria'] = implode(',', $this->data[$this->alias]['categoria']);
		}else{
			$this->data[$this->alias]['categoria'] = null;
		}
		return true;
	}
	
	function beforeFind($queryData){
		if(isset($queryData['conditions']['Boletim.categoria'])){
			$queryData['conditions']['Boletim.categoria REGEXP'] = '(^|,)'.$queryData['conditions']['Boletim.categoria'].'(,|$)';
			unset($queryData['conditions']['Boletim.categoria']);
		}
		return $queryData;
	}
}