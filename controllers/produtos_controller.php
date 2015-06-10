<?php
class ProdutosController extends AppController {

	var $name = 'Produtos';
	var $uses = array('Produto', );
	
	function beforeFilter(){
		parent::beforeFilter();
		//Se é ajax, não traz o layout
		if ( $this->RequestHandler->isAjax()  ){
			$this->layout = null;
		}
	}
	
	//últimas notícias
	function index($noticia_tipo = 'noticia', $filtro_ano = null, $filtro_mes = null){
		$params = array(
			'order' => array('Produto.ordem asc', 'Produto.id DESC'),
			'conditions' => array('Produto.status' => 'P'),
			'contain' => false,
			'limit'	=> 20,
		);
		
		
		$produtos = $this->Produto->find('all', $params);
		
		$this->paginate['Produto'] = $params;

		
		$this->set(compact('produtos'));
	}
}