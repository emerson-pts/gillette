<?php
define('MULTIMIDIA_IMAGE_DIR', SITE_DIR.'webroot/img/upload/multimidias');

class Multimidia extends AppModel {

	var $name = 'Multimidia';
	var $displayField = 'titulo';

	
	var $tipos = array('foto' => 'Foto','video'=>'Vídeo');
	var	$status = array('rascunho' => 'Rascunho', 'em_aprovação' => 'Em Aprovação', 'aprovada' => 'Aprovada',);
	//var $categorias = array('graduado' => 'Graduado', 'shifter' => 'Shifter', 'senior' => 'Sênior', 'junior' => 'Júnior', 'super-cadete' => 'Super Cadete',);

	
	var $actsAs = array(
        'MeioUpload' => array('image' => array(
			'dir' => MULTIMIDIA_IMAGE_DIR,
			'url' => 'upload/multimidias',
        ),)
    );

	
	var $belongsTo = array(
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Galeria' => array(
			'className' => 'Galeria',
			'foreignKey' => 'galeria_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
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
		'tipo' => array(
			'rule' => 'notEmpty',
			'message' => 'Escolha o Tipo'
		),
		'conteudo' => array(
			'rule'=>'notEmpty',
			'required'=>true,
			'allowEmpty'=>false,
			'message'=> 'Informe o embed do Flicker ou do Youtube.',
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
				'Multimidia.id',
				'Multimidia.data_multimidia',
				'Multimidia.created',
				'Multimidia.titulo',
			),
			
			'topLink' => array(
				'Novo multimidia' => array('url' => array('controller' => 'multimidias', 'action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'listFields' => array(
//				'Multimidia.id' => array('table_head_cell_param' => 'class="text-align-center" width="70"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Código'),

				'Multimidia.data_multimidia' => array('table_head_cell_param' => 'class="text-align-left" width="100"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Data', ),
				'Multimidia.titulo' => array('table_head_cell_param' => 'class="text-align-left"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Título'),
				'Multimidia.tipo' => array('table_head_cell_param' => 'class="text-align-left" width="80"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Tipo'),
				//'Multimidia.categoria_formatada' => array('table_head_cell_param' => 'class="text-align-left" width="80"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Categoria'),
				'Multimidia.status_formatado' => array('table_head_cell_param' => 'class="text-align-left" width="80"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Status'),
			),
			
			'defaultOrder' => array($this->alias.'.id' => 'DESC',),
			
//			'showLog' => array('index', 'edit'),
			
			'containIndex' => array('Usuario','Galeria'),
			'containAddEdit' => array('MultimidiaRelacionada', ),
			
	/*		'box_order' => array(
				'Multimidia.id' => 'Código',
				'Multimidia.titulo' => 'Título',
			),
	*/		

			'box_filter' => array(
				'Multimidia.status' => array('title' => 'Filtrar status', 'options' => array('*' => 'Todos',) + $this->status,),
				'Multimidia.usuario_id' => array('title' => 'Filtrar autor', 'options' => array('*' => 'Todos',) + $this->Usuario->find('list', array('order' => 'apelido'))),
				//'Multimidia.categoria' => array('title' => 'Filtrar categoria', 'options' => array('*' => 'Todos',) + $this->categorias,),
			),
			
			'formParams' => array('enctype' => 'multipart/form-data'),
			
			'form'	=> array(
				'data_multimidia'	=> array('label' => 'Data da Notícia', 'class' => 'dateMaskDiaHora', 'type' => 'text', 'default' => date('d/m/Y H:i'), ),
				'galeria_id'	=> array('label' => 'Galeria', 'type'=> 'select', 'empty' => '--Selecione a galeria--', 'options' => $this->Galeria->find('list')),
				'status'		=> array('label' => 'Status', 'type'=> 'select', 'default' => key($this->status), 'options' => $this->status),
				'usuario_id'	=> array('label' => 'Autor', 'type'=> 'select', 'empty' => '--Selecione o autor--', 'default' => $this->usuario['id'], 'options' => $this->Usuario->find('list', array('order'=>'Usuario.status DESC, Usuario.nome'))),
				
				'tipo'			=> array('label' => 'Tipo',  'type' => 'select', 'default' => 'noticia', 'options' => $this->tipos),
				//'categoria'		=> array('label' => 'Categoria', 'multiple'=> 'checkbox', 'div' => 'input checkbox short', 'options' => $this->categorias,'before' => '<span class="right">(se a notícia for específica de uma categoria, selecione-a ao lado)</span>', 'after' => '<span class="clearFix">&nbsp;</span>'),
				'titulo'		=> array('label' => 'Título', 'type' => 'text','maxlength' => 255),
				//'image'			=> array('label' => 'Imagem', 'type' => 'file', 'show_remove' => true, 'show_preview' => '640x480', 'show_preview_url' => '/../'.str_replace('.admin', '', APP_DIR).'/thumbs/'),
				'image_align'	=> array('label' => 'Alinhamento', 'type'=> 'select', 'default' => key($align), 'options' => $align),
				'image_legenda'	=> array('label' => 'Legenda',),
				'conteudo_preview'	=> array('label' => 'Preview do conteúdo', 'cols' => 50, 'rows' => 6,),
				'conteudo'		=> array('label' => 'Embed <small>(Youtube ou flicker)</small>', 'cols' => 50, 'rows' => 15, ),
			),
		);
		
		return $setupAdmin;
	}

	public function __construct($id=false,$table=null,$ds=null){
		parent::__construct($id,$table,$ds);
		$this->virtualFields = array(
			'titulo_olho' => "CONCAT('<b>', `{$this->alias}`.`titulo`, '</b><br />')",
		);
	}
	
	function afterFind($results){
		App::import('Helper', 'text');
		$text = new TextHelper;


		$tipos_url = array(
			'multimidia' 		=> 'multimidia',
		);

		foreach($results AS $key=>$r){
			if(!isset($r[$this->alias])){
				if(isset($r['data_multimidia'])){
					$results[$key]['data_multimidia'] = $r['data_multimidia'] = date('d/m/Y h:i', strtotime($r['data_multimidia']));
					$results[$key]['data_multimidia_data'] = substr($r['data_multimidia'], 0, 10);
					$results[$key]['data_multimidia_ano'] = substr($r['data_multimidia'], 6, 4);
					$results[$key]['data_multimidia_mes'] = substr($r['data_multimidia'], 3, 2);
					$results[$key]['data_multimidia_hora'] = str_replace(':', 'h', substr($r['data_multimidia'], 11, 5));
				}
				
				if(!empty($r['tipo']))
					$results[$key]['tipo_formatado'] = $this->tipos[$r['tipo']];

				if(!empty($r['status']))
					$results[$key]['status_formatado'] = $this->status[$r['status']];
					
				if(isset($r['categoria'])){
					$results[$key]['categoria'] = explode(',', $r['categoria']);
					$results[$key]['categoria_formatada'] = $text->toList(array_flip(array_intersect(array_flip($this->categorias),explode(',', $r['categoria']))), 'e');
				}

			}else{
				
				if(isset($r[$this->alias]['data_multimidia'])){
					$results[$key][$this->alias]['data_multimidia_data'] = substr($r[$this->alias]['data_multimidia'], 0, 10);
					$results[$key][$this->alias]['data_multimidia_ano'] = substr($r[$this->alias]['data_multimidia'], 6, 4);
					$results[$key][$this->alias]['data_multimidia_mes'] = substr($r[$this->alias]['data_multimidia'], 3, 2);
					$results[$key][$this->alias]['data_multimidia_hora'] = str_replace(':', 'h', substr($r[$this->alias]['data_multimidia'], 11, 5));
				}

				
				if(!empty($r[$this->alias]['tipo']))
					$results[$key][$this->alias]['tipo_formatado'] = $this->tipos[$r[$this->alias]['tipo']];
				
				if(!empty($r[$this->alias]['status']))
					$results[$key][$this->alias]['status_formatado'] = $this->status[$r[$this->alias]['status']];
				
				if(isset($r[$this->alias]['categoria'])){
					$results[$key][$this->alias]['categoria'] = explode(',', $r[$this->alias]['categoria']);
					$results[$key][$this->alias]['categoria_formatada'] = $text->toList(array_flip(array_intersect(array_flip($this->categorias),explode(',', $r[$this->alias]['categoria']))), 'e');
				}
				
				//VIDEO EMBED
				if(!empty($r["Multimidia"]["conteudo"])){
					
					//PEGA A LARGURA DO EMBED CADASTRADO
					ereg('width="([0-9]+)"',$r["Multimidia"]["conteudo"],$width_antigo);
					//ereg('height="([0-9]+)"',$r["Multimidia"]["conteudo"],$height_antigo);
					
					if(!empty($width_antigo) && $width_antigo[1]!='620'){
						//Redimensiona o embed
						$results[$key]["Multimidia"]["conteudo"]=str_ireplace($width_antigo[0],'width="620"',$results[$key]["Multimidia"]["conteudo"]);
						
					}
					
				}
				
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
		if(isset($queryData['conditions']['Multimidia.categoria'])){
			$queryData['conditions']['Multimidia.categoria REGEXP'] = '(^|,)'.$queryData['conditions']['Multimidia.categoria'].'(,|$)';
			unset($queryData['conditions']['Multimidia.categoria']);
		}
		return $queryData;
	}
}