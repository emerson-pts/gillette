<?php
class CachesController extends AppController {

	var $name = 'Caches';
	var $uses = null;

	function beforeFilter(){
		parent::beforeFilter();
		$this->cache_dir = preg_replace('/\.admin/', '', CACHE).'views/';
	}

	function index(){
		$this->set('arquivos', scandir($this->cache_dir));
		$this->set('cache_dir', $this->cache_dir);
	}

	function delete($arquivo){
		if(unlink($this->cache_dir.basename($arquivo)))
			$this->Session->setFlash(__('O arquivo '.basename($arquivo).' do cache foi limpo com sucesso!', true),'default',array('class'=>'message_success'));
		else
			$this->Session->setFlash(__('O arquivo '.basename($arquivo).' do cache não foi removido!', true),'default',array('class'=>'message_error'));

		$this->redirect(array('action' => 'index'));
	}
	
	function clearcache(){
		foreach(glob($this->cache_dir.'*.*') as $v)unlink($v);	
		$this->Session->setFlash(__('O cache foi limpo com sucesso!', true),'default',array('class'=>'message_success'));
		$this->redirect(array('action' => 'index'));
	}


	
}
