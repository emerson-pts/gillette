<?php
class HomeController extends AppController {

	var $name = 'Home';
	var $uses = array('Vitrine','Noticia');
	
	function index(){
		
		$this->set('title_for_layout','Home');

		$vitrines = $this->Vitrine->find('all', array(
			'conditions'=>array(
				array('OR' => array('Vitrine.data_inicio' => null, 'Vitrine.data_inicio <=' => date('Y-m-d H:i'))),
				array('OR' => array('Vitrine.data_fim' => null, 'Vitrine.data_fim >=' => date('Y-m-d H:i'))),
			),
			'order' => array('Vitrine.peso DESC', 'Vitrine.id DESC',),
			'limit' => 4,
		));
		
		$noticias = $this->Noticia->find('all', array(
				'conditions' => array('Noticia.tipo' => 'noticia',  'Noticia.status' => 'aprovada',),
				'order' => array('Noticia.data_noticia DESC', 'Noticia.id DESC'),
				'limit'	=> 4,
		));
		
		$this->set(compact('vitrines','noticias'));
	}
}