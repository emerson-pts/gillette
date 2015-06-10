<?php
define('CALENDARIO_IMAGE_DIR', SITE_DIR.'webroot/img/upload/calendarios');

class Calendario extends AppModel {

	var $name = 'Calendario';
	var $displayField = 'titulo';

	var $options = array(
		'tipos' => array()
	);

	var $actsAs = array(
        'MeioUpload' => array('image' => array(
			'dir' => CALENDARIO_IMAGE_DIR,
			'url' => 'upload/calendarios',
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
				'rule'=>array('minLength',1),
				'required'=>true,
				'allowEmpty'=>false,
				'message'=>'O título está em branco.',
			),
		),
		
		'data' => array(
			'rule'=>array('date','dmy'),
			'required'=>true,
			'allowEmpty'=>false,
			'message'=>'Data inválida',
		),

		'data_fim' => array(
			'rule'=>array('date','dmy'),
			'allowEmpty'=>true,
			'required'=>false
		),
		
	);

	function setupAdmin($action = null, $id = null){

		//Lê dados do usuário e armazena na variável usuario do modelo
		App::import('Core', 'CakeSession'); 
        $session = new CakeSession(); 
		if(!$session->started())$session->start();
		$this->usuario = $session->read('Auth.Usuario');
		
		
		
		$setupAdmin = array(
			'displayField' => $this->displayField,
			
			'searchFields' => array(
				'Calendario.id',
				'Calendario.data',
				'Calendario.data_fim',
				'Calendario.created',
				'Calendario.titulo',
			),
			
			'topLink' => array(
				'Novo evento' => array('url' => array('controller' => 'calendarios', 'action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'listFields' => array(
				'Calendario.id' => array('table_head_cell_param' => 'class="text-align-center" width="70"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Código'),
				'Calendario.data_inicio_fim' => array('table_head_cell_param' => 'class="text-align-left" width="70"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Data', ),
				'Calendario.hora' => array('table_head_cell_param' => 'class="text-align-left" width="70"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Hora', ),
//				'Calendario.destaque_formatado' => array('table_head_cell_param' => 'class="text-align-center" width="60"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Destaque', ),
				'Calendario.tipo_formatado' => array('table_head_cell_param' => 'class="text-align-center" width="100"', 'table_body_cell_param' => 'class="text-align-center"', 'label' => 'Tipo'),
				'Calendario.titulo' => array('table_head_cell_param' => 'class="text-align-left"', 'table_body_cell_param' => 'class="text-align-left"', 'label' => 'Título'),
			),
			
			'defaultOrder' => array($this->alias.'.data' => 'ASC',),
			
//			'showLog' => array('index', 'edit'),
			
			'containIndex' => array(),
			'containAddEdit' => array(),
			
	/*		'box_order' => array(
				'Noticia.id' => 'Código',
				'Noticia.titulo' => 'Título',
			),
	*/		
			'box_filter' => array(
				//'Calendario.tipo' => array('title' => 'Filtrar tipo', 'options' => array('*' => 'Todos',) + $this->tipos,),
				'Calendario.destaque' => array('title' => 'Filtrar destaque', 'options' => array('*' => 'Todos', '1' => 'Sim', '0' => 'Não')),
			),
			
			'formParams' => array('enctype' => 'multipart/form-data'),
			
			'form'	=> array(
				'data'			=> array('label' => 'Data', 'class' => 'dateMask datepicker', 'type' => 'text', 'default' => date('d/m/Y'), ),
				'data_fim'		=> array('label' => 'Data término', 'class' => 'dateMask datepicker', 'type' => 'text', ),
				'hora'			=> array('label' => 'Hora',),
				//'destaque'		=> array('label' => 'Destaque', 'type' => 'select', 'default' => '0', 'options' => array('0' => 'Não', '1' => 'Sim')),
				'tipo'			=> array('label' => 'Tipo', 'multiple'=> 'checkbox', 'div' => 'input checkbox', 'options' => $this->options['tipos'],'after' => '(se o evento for de um tipo específico, selecione-o ao lado)<span class="clearFix">&nbsp;</span>'),
				'titulo'		=> array('label' => 'Título', 'type' => 'textarea', 'cols' => 50, 'rows' => 6, 'limit' => 255),
				//'image'			=> array('label' => 'Imagem', 'type' => 'file', 'show_remove' => true, 'show_preview' => '640x480', 'show_preview_url' => '/../'.str_replace('.admin', '', APP_DIR).'/thumbs/'),
				//'image_legenda'	=> array('label' => 'Legenda',),
				'descricao_preview'	=> array('label' => 'Resumo da descrição', 'cols' => 40, 'rows' => 3,'limit'=>200,'after'=>'<br/><small>Descrição resumida das datas que aparecerão abaixo do menu principal.<br/>Caso fique em branco não aparecerá na listagem abaixo do menu.</small>'),
				'descricao'		=> array('label' => 'Descrição', 'div' => 'ckeditor', 'class' => 'ckeditor', 'cols' => 50, 'rows' => 15, ),
			),
		);
		
		
		//Se não tem categorias
		if(empty($this->options['tipos'])){
			//Remove o campo do filtro, formulário e lista
			unset($setupAdmin['box_filter']['Calendario.tipo']);
			unset($setupAdmin['listFields']['Calendario.tipo_formatado']);
			unset($setupAdmin['form']['tipo']);
		}
		
		
		
		return $setupAdmin;
	}

	public function __construct($id=false,$table=null,$ds=null){
		parent::__construct($id,$table,$ds);
		$this->virtualFields = array(
			'destaque_formatado' => 'IF(`'.$this->alias.'`.`destaque` = "1", "Sim", "Não")',
			'data_inicio_fim' => 'IF(NOT ISNULL(`'.$this->alias.'`.`data_fim`), CONCAT(DATE_FORMAT(`'.$this->alias.'`.`data`, "%d/%m/%Y"), " até ", DATE_FORMAT(`'.$this->alias.'`.`data_fim`, "%d/%m/%Y")), DATE_FORMAT(`'.$this->alias.'`.`data`, "%d/%m/%Y"))',
			'dia_semana' => 'date_format('.$this->alias.'.data,\'%w\')',
			
		);
	}
	

	function afterFindExecute($result){
		$result = parent::afterFindExecute($result);
	
		App::import('Helper', 'text');
		$text = new TextHelper;

		App::import('Helper', 'formatacao');
		$formatacao = new FormatacaoHelper;

		if(isset($result['data'])){
			if(!isset($result['data_original'])){
				$result['data'] = date('d/m/Y', strtotime($result['data']));
			}
			$result['data_ano'] = substr($result['data'], 6, 4);
			$result['data_mes'] = substr($result['data'], 3, 2);
			$result['data_dia'] = substr($result['data'], 0, 2);
			$result['data_mes_formatada'] = $formatacao->meses[(int)$result['data_mes']-1];
		}
		if(isset($result['tipo'])){
			$result['tipo'] = explode(',', $result['tipo']);
			$result['tipo_formatado'] = $text->toList(array_flip(array_intersect(array_flip($this->tipos),explode(',', $result['tipo']))), 'e');
		}
		
		if(isset($result['dia_semana'])){
			$result['dia_semana_formatado'] = $formatacao->diasDaSemana[$result['dia_semana']];
		}

		return $result;
	}
	
	function beforeSave(){
		if(!empty($this->data[$this->alias]['tipo'])){
			$this->data[$this->alias]['tipo'] = implode(',', $this->data[$this->alias]['tipo']);
		}else{
			$this->data[$this->alias]['tipo'] = null;
		}
		return true;
	}
	function getAnos(){
		$anos = $this->find('all',array(
			'fields'	=> array(
				'DATE_FORMAT('.$this->alias.'.data, "%Y") as ano',
			),
			'group'		=> array('ano'),
			'order'		=> 'ano DESC',
		));
		
		return Set::extract('/./0/ano',$anos);
	}
}