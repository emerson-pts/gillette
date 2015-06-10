<?php
define('NOTICIA_IMAGE_DIR', SITE_DIR.'webroot/img/upload/noticias');

class Noticia extends AppModel {

	var $name = 'Noticia';
	var $displayField = 'titulo';

	var $options = array(
		'tipos' 		=> array('noticia' => 'Notícia',),
		'tipos_url'		=> array(
			'noticia' 		=> 'noticia',
		),
		'status' 		=> array('rascunho' => 'Rascunho', 'em_aprovação' => 'Em Aprovação', 'aprovada' => 'Aprovada',),
		'categorias'	=> array(),
	);

	
	var $actsAs = array(
        'MeioUpload' => array('image' => array(
			'dir' => NOTICIA_IMAGE_DIR,
			'url' => 'upload/noticias',
        ),)
    );

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasAndBelongsToMany = array(
        'NoticiaRelacionada' => array(
			'className' => 'Noticia',
			'order'		=> 'NoticiaRelacionada.data_noticia DESC',
			'joinTable' => 'noticias_noticias',
			'foreignKey' => 'noticia_id',
			'associationForeignKey' => 'related_id',
			'fields' => array('id', 'tipo', 'titulo', 'friendly_url', 'data_noticia',),
		),
	);

	var $belongsTo = array(
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'usuario_id',
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
				'rule'=>array('minLength',1),
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
				'Noticia.id',
				'Noticia.data_noticia',
				'Noticia.created',
				'Noticia.titulo',
				'Noticia.olho',
				'Noticia.conteudo',
			),
			
			'topLink' => array(
				'Nova notícia' => array('url' => array('controller' => 'noticias', 'action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'listFields' => array(
				'Noticia.id' => array('table_head_cell_param' => 'class="text-align-center" width="50"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Cód.'),
				'Noticia.data_noticia' => array('table_head_cell_param' => 'class="text-align-left" width="120"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Data da Notícia', ),
				'Noticia.tipo' => array('table_head_cell_param' => 'class="text-align-left" width="100"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Tipo', ),
				'Noticia.titulo_olho' => array('table_head_cell_param' => 'class="text-align-left"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Título/Olho'),
				'Usuario.apelido' => array('table_head_cell_param' => 'class="text-align-left" width="80"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Apelido'),
				'Noticia.categoria_formatada' => array('table_head_cell_param' => 'class="text-align-left" width="80"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Categoria'),
				'Noticia.status_formatado' => array('table_head_cell_param' => 'class="text-align-left" width="80"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Status'),
			),
			
			'defaultOrder' => array($this->alias.'.id' => 'DESC',),
			
//			'showLog' => array('index', 'edit'),
			
			'containIndex' => array('Usuario',),
			'containAddEdit' => array('NoticiaRelacionada', ),
			
	/*		'box_order' => array(
				'Noticia.id' => 'Código',
				'Noticia.titulo' => 'Título',
			),
	*/		

			'box_filter' => array(
				'Noticia.tipo' => array('title' => 'Filtrar tipo', 'options' => array('*' => 'Todos',) + $this->options['tipos'],),
				'Noticia.categoria' => array('title' => 'Filtrar categoria', 'options' => array('*' => 'Todos',) + $this->options['categorias'],),
				'Noticia.status' => array('title' => 'Filtrar status', 'options' => array('*' => 'Todos',) + $this->options['status'],),
				'Noticia.usuario_id' => array('title' => 'Filtrar autor', 'options' => array('*' => 'Todos',) + $this->Usuario->find('list', array('order' => 'apelido'))),
			),
			
			'formParams' => array('enctype' => 'multipart/form-data'),
			
			'form'	=> array(
				'tipo'			=> array('label' => 'Tipo',  'type' => 'select', 'default' => 'noticia', 'options' => $this->options['tipos']),
				'data_noticia'	=> array('label' => 'Data da Notícia', 'class' => 'dateMaskDiaHora', 'type' => 'text', 'default' => date('d/m/Y H:i'), ),
				'status'		=> array('label' => 'Status', 'type'=> 'select', 'default' => key($this->options['status']), 'options' => $this->options['status']),
				'usuario_id'	=> array('label' => 'Autor', 'type'=> 'select', 'empty' => '--Selecione o autor--', 'default' => $this->usuario['id'], 'options' => $this->Usuario->find('list', array('order'=>'Usuario.status DESC, Usuario.nome'))),
				'categoria'		=> array('label' => 'Categoria', 'multiple'=> 'checkbox', 'div' => 'input checkbox short', 'options' => $this->options['categorias'],'before' => '<span class="right">(se a notícia for específica de uma categoria, selecione-a ao lado)</span>', 'after' => '<span class="clearFix">&nbsp;</span>'),
				'titulo'		=> array('label' => 'Título', 'type' => 'textarea', 'cols' => 50, 'rows' => 6, 'limit' => 255),
				'olho'			=> array('label' => 'Olho', 'cols' => 50, 'rows' => 6,),
				'image'			=> array('label' => 'Imagem', 'type' => 'file', 'show_remove' => true, 'show_preview' => '640x480', 'show_preview_url' => '/../'.str_replace('.admin', '', APP_DIR).'/thumbs/'),
				'image_align'	=> array('label' => 'Alinhamento', 'type'=> 'select', 'default' => key($align), 'options' => $align),
				'image_legenda'	=> array('label' => 'Legenda Imagem',),
				//'conteudo_preview'	=> array('label' => 'Preview do conteúdo', 'cols' => 50, 'rows' => 6,),
				'conteudo'		=> array('label' => 'Notícia', 'div' => 'ckeditor', 'class' => 'ckeditor', 'cols' => 50, 'rows' => 15, ),
			),
		);

		//Se não tem categorias
		if(empty($this->options['categorias'])){
			//Remove o campo do filtro, formulário e lista
			unset($setupAdmin['box_filter']['Noticia.categoria']);
			unset($setupAdmin['listFields']['Noticia.categoria_formatada']);
			unset($setupAdmin['form']['categoria']);
		}

		//Se tem somente um tipo de notícia
		if(count($this->options['tipos']) == 1){
			//Remove campo do filtro
			unset($setupAdmin['listFields']['Noticia.tipo']);
			unset($setupAdmin['box_filter']['Noticia.tipo']);
			$setupAdmin['form']['tipo']['type'] = 'hidden';
		}
		
		return $setupAdmin;
	}

	public function __construct($id=false,$table=null,$ds=null){
		parent::__construct($id,$table,$ds);
		$this->virtualFields = array(
			'titulo_olho' => "CONCAT('<b>', `{$this->alias}`.`titulo`, '</b><br />', `{$this->alias}`.`olho`)",
		);
	}
	
	//Alterações a serem no find 
	function afterFindExecute($result){
		$result = parent::afterFindExecute($result);//Executa ações da classe pai

		App::import('Helper', 'text');
		$text = new TextHelper;

		if(isset($result['data_noticia'])){
			$result['data_noticia'] = substr($result['data_noticia'], 0, -3);
			$result['data_noticia_data'] = substr($result['data_noticia'], 0, 10);
			$result['data_noticia_ano'] = substr($result['data_noticia'], 6, 4);
			$result['data_noticia_mes'] = substr($result['data_noticia'], 3, 2);
			$result['data_noticia_dia'] = substr($result['data_noticia'], 0, 2);
			$result['data_noticia_hora'] = str_replace(':', 'h', substr($result['data_noticia'], 11, 5));
		}

		if(isset($result['friendly_url']) && isset($result['data_noticia']) && isset($result['tipo'])){
			$result['link'] = '/'.$this->options['tipos_url'][$result['tipo']].'/'.$result['data_noticia_ano'].'/'.$result['data_noticia_mes'].'/'.$result['friendly_url'];
		}
		
		if(!empty($result['tipo']))
			$result['tipo_formatado'] = $this->options['tipos'][$result['tipo']];

		if(!empty($result['status']))
			$result['status_formatado'] = $this->options['status'][$result['status']];
			
		if(isset($result['categoria'])){
			$result['categoria'] = explode(',', $result['categoria']);
			$result['categoria_formatada'] = $text->toList(array_flip(array_intersect(array_flip($this->options['categorias']),explode(',', $result['categoria']))), 'e');
		}
		return $result;
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
		if(isset($queryData['conditions']['Noticia.categoria'])){
			$queryData['conditions']['Noticia.categoria REGEXP'] = '(^|,)'.$queryData['conditions']['Noticia.categoria'].'(,|$)';
			unset($queryData['conditions']['Noticia.categoria']);
		}
		return $queryData;
	}
}