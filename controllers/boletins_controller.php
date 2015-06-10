<?php
class BoletinsController extends AppController {

	var $name = 'Boletins';
	var $uses = array('Boletim', );

	function beforeFilter(){
		parent::beforeFilter();
		//Se é ajax, não traz o layout
		if ( $this->RequestHandler->isAjax()  ){
			$this->layout = null;
		}
	}
	
	function beforeRender(){
		parent::beforeRender();
		
		/*$boletims_anos = $this->Boletim->find('all', array('fields' => array('DATE_FORMAT(Boletim.data_boletim, "%Y") ano'), 'recursive' => 0, 'conditions' => array('Boletim.status' => 'aprovada'), 'group' => 'ano',));
		$boletims_anos = Set::extract('{n}.0.ano', $boletims_anos);
		$this->set('boletims_anos', $boletims_anos);*/
	}

	
	

	function index($friendly_url=null){
	
	
	
		if(!empty($this->params['originalArgs']['params']['pass'][1])){
			$boletim= $this->Boletim->find('first',array(
				'fields'=>array('Boletim.id','Boletim.data','Boletim.titulo','Boletim.conteudo'),
				'conditions' => array('Boletim.status' => 'aprovada','Boletim.friendly_url' => $this->params['originalArgs']['params']['pass'][1],),
			));
		}else{
			$boletim= $this->Boletim->find('first',array(
				'fields'=>array('Boletim.id','Boletim.data','Boletim.titulo','Boletim.conteudo'),
				'conditions' => array('Boletim.status' => 'aprovada',),
				'order' => array('Boletim.data DESC', 'Boletim.id DESC'),
			));
		}
		
		if(empty($boletim)){
			$this->Session->setFlash('Nenhum boletim está disponível no momento.','default',array('class'=>'message_error'));
			$this->render('/pages/blank');
			return;
		}
		 $boletins= $this->Boletim->find('all',array(
		 	'fields'=>array('Boletim.id','Boletim.data','Boletim.titulo','Boletim.friendly_url','Boletim.image'),
			'order' => array('Boletim.data DESC', 'Boletim.id DESC'),
			'conditions' => array('Boletim.status' => 'aprovada','Boletim.id !='=>$boletim['Boletim']['id']),

		));
			
		
		

		//aplica filtro no sidebar
		$this->boletinsSidebarFilter = Set::extract('/Boletim/id', $boletins);

		$this->set(compact('boletins','boletim'));
	}
	
}