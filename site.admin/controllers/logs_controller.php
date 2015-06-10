<?php
class LogsController extends AppController {
	var $name = 'Logs';

	function index(){
		if(!preg_match('/^\/'.$this->params['controller'].'\//', $this->referer())){
			$this->set('back', $this->referer());
		}

		if(!$this->data['Log'] || !$this->data['Log']['data'] || !$this->data['Log']['model']){
			$msg = 'Os dados para leitura do log não foram informados corretamente.';
			$data = array();
		}else{
			$msg = true;
			if(empty($this->data['Log']['models']))$this->data['Log']['models'] = array($this->data['Log']['model']);
			
			if(is_string($this->data['Log']['data']))$this->data['Log']['data'] = unserialize($this->data['Log']['data']);
			if(is_string($this->data['Log']['models']))$this->data['Log']['models'] = unserialize($this->data['Log']['models']);
			
			$data = $this->Log->findAllLog($this->data['Log']['data'], $this->data['Log']['models'], array('model' => $this->data['Log']['model']));
		}
		
		if ( $this->RequestHandler->isAjax() ){
			$this->layout = 'ajax';
		}
		$this->set('msg', $msg);
		$this->set('data', $data);
	}
}
