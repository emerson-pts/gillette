<?php
class NoticiasController extends AppController {

	var $name = 'Noticias';
	var $uses = array('Noticia', );
	var	$noticia_tipos = array('noticia' => 'Notícia',);
	var	$noticias_tipos = array('noticia' => 'Notícias',);
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
		$this->set('noticia_tipos', $this->noticia_tipos);
		$this->set('noticias_categorias', $this->Noticia->options['categorias']);

		$noticias_anos = $this->Noticia->find('all', array('fields' => array('DATE_FORMAT(Noticia.data_noticia, "%Y") ano'), 'recursive' => 0, 'conditions' => array('Noticia.status' => 'aprovada'), 'group' => 'ano',));
		$noticias_anos = Set::extract('{n}.0.ano', $noticias_anos);
		$this->set('noticias_anos', $noticias_anos);
	}

	function _valida_tipo($noticia_tipo){
		if(empty($this->noticia_tipos[$noticia_tipo])){
			$this->Session->setFlash(__('Ops! O tipo de conteúdo solicitado é inválido (código 1)', true),'default',array('class'=>'message_error'));
			$this->redirect('index');			
		}
		$this->set('title_for_layout', $this->noticias_tipos[$noticia_tipo]);
		return true;
	}

	//últimas notícias
	function index($noticia_tipo = 'noticia', $filtro_ano = null, $filtro_mes = null, $friendly_url = null){
	
		if(!empty($this->params['originalArgs'])){
			
		}
		
		if(!empty($friendly_url)){
			$this->noticia($noticia_tipo, $filtro_ano, $filtro_mes, $friendly_url);
			$this->render('noticia');
			return;			
		}
		
		$this->_valida_tipo($noticia_tipo);
		if(!empty($filtro_ano) && !is_numeric($filtro_ano))$filtro_ano = null;
		if(!empty($filtro_mes) && !is_numeric($filtro_mes))$filtro_mes = null;
		
		$params = array(
			'order' => array('Noticia.data_noticia DESC', 'Noticia.id DESC'),
			'conditions' => array('Noticia.tipo' => $noticia_tipo, 'Noticia.status' => 'aprovada'),
			'contain' => false,
			'limit'	=> 9,
		);
		
		
		$this->paginate['Noticia'] = $params;

		if(!empty($filtro_ano) && is_numeric($filtro_ano)){
			$this->paginate['Noticia']['conditions']['Noticia.data_noticia LIKE'] = $filtro_ano.'-'.(!empty($filtro_mes) ? $filtro_mes.'-' : '').'%';
		}

		if(!empty($this->params['named']['categoria'])){
			if(isset($this->Noticia->categorias[$this->params['named']['categoria']]))
				$this->paginate['Noticia']['conditions'][] = array('OR' => array(
					'Noticia.categoria' => null,
					'Noticia.categoria REGEXP' => '(^|,)'.$this->params['named']['categoria'].'(,|$)',
				));
			else
				unset($this->params['named']['categoria']);
		}
		
		$noticias = $this->paginate('Noticia');

		//aplica filtro no sidebar
		$this->noticiasSidebarFilter = Set::extract('/Noticia/id', $noticias);

		$this->set(compact('noticias', 'noticia_tipo', 'filtro_ano', 'filtro_mes'));
	}
	
	//Notícia
	function noticia($noticia_tipo= null, $filtro_ano=null, $filtro_mes=null, $friendly_url=null){
		$this->_valida_tipo($noticia_tipo);

		if (!$filtro_ano || !$filtro_mes || !$friendly_url) {
			$this->Session->setFlash(__('Ops! Página não encontrada', true),'default',array('class'=>'message_error'));
			$this->redirect('index');
		}
		
		$noticia=$this->Noticia->find('first',array('conditions'=>array(
			'Noticia.tipo' => $noticia_tipo,
			'Noticia.data_noticia LIKE' => $filtro_ano.'-'.$filtro_mes.'%',
			'Noticia.friendly_url' => $friendly_url
		)));

		
		$noticias=$this->Noticia->find('all',array(
			'conditions'=>array(
				'Noticia.tipo' => $noticia_tipo,
			),
			'order'=>'Noticia.data_noticia DESC',
			'limit'=>14
		));
		
		
		if (!$noticia){
			$this->Session->setFlash(__('Ops! Notícia não encontrada', true),'default',array('class'=>'message_error'));
			$this->redirect('index');	
		}


		$this->noticiasSidebarFilter = array($noticia['Noticia']['id']);

		$this->set('title_for_layout', $this->noticia_tipos[$noticia_tipo].': '.$noticia['Noticia']['titulo']);
				
		$this->set(compact('noticia','noticias','noticia_tipo', 'filtro_ano', 'filtro_mes'));
	}
}