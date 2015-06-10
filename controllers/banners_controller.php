<?php
class BannersController extends AppController {

	var $name = 'Banners';
	var $uses = array('Banner');
	
	function index($area){
		
		if(!isset($this->Banner->areas[$area])){
			$this->Session->setFlash(__('Ops! O tipo de conteœdo solicitado  inv‡lido (c—digo 1)', true),'default',array('class'=>'message_error'));
			$this->redirect('index');			
		}
		$this->set('title_for_layout',preg_replace('/ ?\(.*$/', '', $this->Banner->areas[$area]));

		$banners = $this->Banner->getBanner($area,null,'Banner.peso');

		$this->set(compact('banners'));
	}
}