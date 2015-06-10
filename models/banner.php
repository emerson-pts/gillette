<?php
define('BANNER_IMAGE_DIR', SITE_DIR.'webroot/img/upload/banners');

class Banner extends AppModel {

	var $name = 'Banner';
	var $displayField = 'titulo';
    var $order = 'Banner.peso DESC';

	var $areas = array(
		'patrocinadores' => 'Patrocinadores (143x143 pixels)',
		'parceiros' => 'Parceiros (620x150 pixels)',
		'rodape-patro' => 'Rodapé - Patrocinadores (116x116 pixels)',
		'rodape-apoio' => 'Rodapé - Apoio (116x116 pixels)',
		'rodape-superv' => 'Rodapé - Supervisão (116x116 pixels)',
		'pub-topo' => 'Publicidade - Topo (728x90 pixels)',
		'pub-lateral' => 'Publicidade - Lateral (300x250 pixels)',
		'pub-inferior' => 'Publicidade - Inferior (116x116 pixels)',
	);

	var $actsAs = array(
        'MeioUpload' => array(
			'imagem' => array(
				'allowedMime' => array('image/jpeg', 'image/pjpeg', 'image/png', 'image/gif', 'image/bmp', 'image/x-icon', 'image/vnd.microsoft.icon', 'application/x-shockwave-flash',),
				'allowedExt' => array('.jpg', '.jpeg', '.png', '.gif', '.bmp', '.ico', '.swf'),
				'dir' => BANNER_IMAGE_DIR,
				'url' => 'upload/banners',
			),
		),
	);
	
	
	var $validate = array(
		'area'	=> array(
			'rule' => 'notEmpty',
			'message' => 'Selecione a área',
		),
		'titulo' => array(
			'rule' => 'notEmpty',
			'message' => 'Por favor, informe o título',
		),
		'peso'	=> array(
			'rule' => array('between', 1, 99),
			'message' => 'O peso informado é inválido.',
			'required'=>true,
			'allowEmpty'=>false,
		),
	);

	function beforeValidate(){
		switch($this->data[$this->alias]['area']){
			case 'patrocinadores':$width = 143; $height = 143;break;
			/*
			case 'rodape-patro':
			case 'rodape-apoio':
			case 'rodape-superv':$width = 116; $height = 60;break;
			*/
			default: return true;
		}
		
		$this->actsAs['MeioUpload']['imagem']['length'] = array(
			'minWidth' => $width,
			'maxWidth' => $width,
			'minHeight' => $height,
			'maxHeight' => $height,
			'aspectRatio' => 0,
		);
		
		$this->Behaviors->attach('MeioUpload', $this->actsAs['MeioUpload']);
		return true;
	}

	public function __construct($id=false,$table=null,$ds=null){
		parent::__construct($id,$table,$ds);

		$areas = array();
		foreach($this->areas AS $key=>$value){
			$areas[] = 'WHEN "'.$key.'" THEN "'.$value.'"';
		}
		
		$this->virtualFields = array(
			'ativo' => "IF(
				(ISNULL(`{$this->alias}`.`data_inicio`) AND ISNULL(`{$this->alias}`.`data_fim`))
				OR (ISNULL(`{$this->alias}`.`data_inicio`) AND `{$this->alias}`.`data_fim` >= DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'))
				OR (DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i') BETWEEN `{$this->alias}`.`data_inicio` AND `{$this->alias}`.`data_fim`)
				OR (ISNULL(`{$this->alias}`.`data_fim`) AND `{$this->alias}`.`data_inicio` <= DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'))
				, 1, 0)",
			'area_formatada' => 'CASE `'.$this->alias.'`.area '.implode("\r\n", $areas).' END',
		);
	}

	//Carrega banners
	function getBanner($area = null, $limit = 1, $order = 'POW( RAND(), Banner.peso ) ASC' /*ordem aleatória levando em conta a prioridade de exibição*/){
		$params = array(
			'conditions' => array(
				array(
					array('OR' => array('Banner.data_inicio' => null, 'Banner.data_inicio <=' => date('Y-m-d H:i'))),
					array('OR' => array('Banner.data_fim' => null, 'Banner.data_fim >=' => date('Y-m-d H:i'))),
				),
			),
			'order' => $order,
			'limit'	=> $limit,
		);

		if(!empty($area)){
			$params['conditions']['Banner.area'] = $area;
		}

		return $this->find(($limit == 1 ? 'first' : 'all'), $params);
	}

	function setupAdmin($action = null, $id = null){

		$setupAdmin = array(
			'displayField' => $this->displayField,
			
			'searchFields' => array(
				$this->alias.'.id',
				$this->alias.'.area',
				$this->alias.'.titulo',
				$this->alias.'.url',
			),
			
			'topLink' => array(
				'Novo Banner' => array('url' => array('action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'defaultOrder' => array($this->alias.'.area', $this->alias.'.peso' => 'DESC', $this->alias.'.id' => 'DESC',),
						
			'containIndex' => false,
			'containAddEdit' => false,

			'box_filter' => array(
				$this->alias.'.area' => array('title' => 'Filtrar Área', 'options' => array('*' => 'Todas',) + $this->areas,),
			),
			
			'formParams' => array('enctype' => 'multipart/form-data'),

			'form'	=> array(
				'area'			=> array('label' => 'Área', 'type' => 'select', 'empty' => '--Selecione--', 'options' => $this->areas),
				'peso'			=> array('label' => 'Prioridade', 'type' => 'text', 'maxlength' => 2, 'size' => 5, 'after' => ' (<small>Valores entre 1 e 99, utilizado para defirnir a prioridade de exibição</small>)', 'class' => 'txtbox-auto onlyNumber',),
				'titulo'		=> array('label' => 'Título', 'type' => 'text', 'maxlenght' => 100),
				'url'			=> array('label' => 'Url',),
				'imagem'		=> array('label' => 'Imagem', 'type' => 'file', 'show_remove' => true, 'show_preview' => '609x270', 'show_preview_url' => '/../'.str_replace('.admin', '', APP_DIR).'/thumbs/'), 
				'data_inicio' 	=> array('label' => 'Data de início', 'type' => 'text', 'class' => 'dateMaskDiaHora',),
				'data_fim' 		=> array('label' => 'Data de término', 'type' => 'text', 'class' => 'dateMaskDiaHora',),
			),
			
		);
		
		return $setupAdmin;
	}

}