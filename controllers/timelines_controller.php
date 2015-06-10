<?php
class TimelinesController extends AppController {

	var $name = 'Timelines';
	
	function index(){
		//Carrega timelines
		$timelines = $this->Timeline->find('all',array('order' => array('Timeline.ano' => 'DESC' )));

		//Não encontrou...
		if (!$timelines){
			$this->Session->setFlash(__('Ops! Página não encontrada', true),'default',array('class'=>'message_error'));
			$this->render('/pages/blank');
			return;
		}

		$this->set(compact('timelines'));
	}
}