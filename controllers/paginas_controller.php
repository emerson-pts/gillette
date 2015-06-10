<?php
class PaginasController extends AppController {

	var $name = 'Paginas';
	
	function index($friendly_url = null){

		//Se não informou a página...
		if (!$friendly_url) {
			$this->Session->setFlash(__('Ops! Página não encontrada', true),'default',array('class'=>'message_error'));
			$this->redirect('index');
		}

		//Tenta localizar a página
		$pagina=$this->Pagina->find('first',array('conditions'=>array(
			'Pagina.friendly_url' => $friendly_url
		)));

		//Não encontrou...
		if (!$pagina){
			$this->Session->setFlash(__('Ops! Página não encontrada', true),'default',array('class'=>'message_error'));
			$this->render('/pages/blank');
			return;
		}

		//Seta título da página
		$this->set('title_for_layout', $pagina['Pagina']['titulo']);
		$this->set(compact('pagina'));
	}
}