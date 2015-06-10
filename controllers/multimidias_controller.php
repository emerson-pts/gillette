<?php
class MultimidiasController extends AppController {

	var $name = 'Multimidias';
	var $uses = array('Multimidia', );
	var	$multimidia_tipos = array('foto' => 'Foto','video'=>'Vídeo');
	var	$multimidias_tipos = array('foto' => 'Fotos','video'=>'Vídeos');
	//var	$cacheAction = "5 seconds";
		

	function beforeFilter(){
		parent::beforeFilter();
		//Se é ajax, não traz o layout
		if ( $this->RequestHandler->isAjax()  ){
			$this->layout = null;
		}
	}
	
	function beforeRender(){
		parent::beforeRender();
		$this->set('multimidia_tipos', $this->multimidia_tipos);
		$this->set('multimidias_tipos', $this->multimidias_tipos);
		$this->set('multimidias_categorias', $this->Multimidia->categorias);

		$multimidias_anos = $this->Multimidia->find('all', array('fields' => array('DATE_FORMAT(Multimidia.data_multimidia, "%Y") ano'), 'recursive' => 0, 'conditions' => array('Multimidia.status' => 'aprovada'), 'group' => 'ano',));
		$multimidias_anos = Set::extract('{n}.0.ano', $multimidias_anos);
		$this->set('multimidias_anos', $multimidias_anos);
	}

	
	function _valida_tipo($multimidia_tipo){
		if(empty($this->multimidia_tipos[$multimidia_tipo])){
			$this->Session->setFlash(__('Ops! O tipo de conteúdo solicitado é inválido (código 1)', true),'default',array('class'=>'message_error'));
			$this->redirect('/');			
		}
		$this->set('title_for_layout', $this->multimidias_tipos[$multimidia_tipo]);
		return true;
	}
	
	
	//últimas notícias
	function index($multimidia_tipo = 'video',$filtro_ano = null, $filtro_mes = null){
		$this->_valida_tipo($multimidia_tipo);
		if(!empty($filtro_ano) && !is_numeric($filtro_ano))$filtro_ano = null;
		if(!empty($filtro_mes) && !is_numeric($filtro_mes))$filtro_mes = null;
		
		$params = array(
			'order' => array('Multimidia.data_multimidia DESC', 'Multimidia.id DESC'),
			'conditions' => array('Multimidia.status' => 'aprovada','Multimidia.tipo' => $multimidia_tipo,),
			'contain' => false,
			'limit'	=> 5,
		);
		
		$this->paginate['Multimidia'] = $params;

		if(!empty($filtro_ano) && is_numeric($filtro_ano)){
			$this->paginate['Multimidia']['conditions']['Multimidia.data_multimidia LIKE'] = $filtro_ano.'-'.(!empty($filtro_mes) ? $filtro_mes.'-' : '').'%';
		}

		if(!empty($this->params['named']['titulo'])){
			$this->paginate['Multimidia']['conditions']['Multimidia.friendly_url'] = $this->params['named']['titulo'];
		}

		if(!empty($this->params['named']['categoria'])){
			if(isset($this->Multimidia->categorias[$this->params['named']['categoria']]))
				$this->paginate['Multimidia']['conditions'][] = array('OR' => array(
					'Multimidia.categoria' => null,
					'Multimidia.categoria REGEXP' => '(^|,)'.$this->params['named']['categoria'].'(,|$)',
				));
			else
				unset($this->params['named']['categoria']);
		}
		
		$multimidias = $this->paginate('Multimidia');

		//aplica filtro no sidebar
		$this->multimidiasSidebarFilter = Set::extract('/Multimidia/id', $multimidias);

		$this->set(compact('multimidias','multimidia_tipo','filtro_ano', 'filtro_mes'));
	}
	
}