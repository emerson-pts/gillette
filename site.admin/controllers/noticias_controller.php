<?php
class NoticiasController extends AppController {

	var $name = 'Noticias';
	
	function index(){
		$this->_admin_index();
	}

	function add(){
		$this->helpers[] = 'jmycake';
		$this->_admin_add();
	}

	function edit($id = null){
		$this->helpers[] = 'jmycake';
		$this->_admin_edit($id);
	}

	function delete($id = null){
		$this->_admin_delete($id);
	}

	function autocomplete(){
		$conf = array(
			'itemLabel' => 'CONCAT(Noticia.titulo, " (", DATE_FORMAT(Noticia.data_noticia, "%d/%m/%Y %h:%i"), " - cód. " COLLATE utf8_bin, Noticia.id, ")")',
//			'conditions'=> array('Noticia.tipo' => 'noticia')
		);
		$this->_autocomplete($conf);
	}
}