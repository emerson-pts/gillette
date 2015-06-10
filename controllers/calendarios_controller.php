<?php
class CalendariosController extends AppController {

	var $name = 'Calendarios';		
	var	$cacheAction = false;

	function index($filtro_ano = null, $filtro_mes = null){
		$this->helpers[] = 'Calendar';
		if(!empty($this->params['named']['ano']))$filtro_ano = $this->params['named']['ano'];
		if(!empty($this->params['named']['mes']))$filtro_mes = $this->params['named']['mes'];
		
		//Seta filtros padrões
//		if(empty($filtro_ano))$filtro_ano = date('Y');
//		if(empty($filtro_mes))$filtro_mes = date('m');
				
		$params = array(
			'order' => array('Calendario.data ASC', 'Calendario.hora ASC'),
			'conditions' => array(),
			'contain' => false,
		);
		
		if(!empty($filtro_ano)){
			$params['conditions']['OR'] = array(
				'Calendario.data LIKE' => $filtro_ano.'-'.(!empty($filtro_mes) ? $filtro_mes.'-%' : ''),
				'Calendario.data_fim LIKE' => $filtro_ano.'-'.(!empty($filtro_mes) ? $filtro_mes.'-%' : ''),
			);
		}
		
		
		if(!empty($this->params['named']['tipo'])){
			if(isset($this->Calendario->options['tipos'][$this->params['named']['tipo']]))
				$params['conditions'][] = array('OR' => array(
					'Calendario.tipo' => null,
					'Calendario.tipo REGEXP' => '(^|,)'.$this->params['named']['tipo'].'(,|$)',
				));
			else
				unset($this->params['named']['tipo']);
		}
		
		/*
		$calendarios = $this->Calendario->find('all', array(
			'order' => array('Calendario.data ASC', 'Calendario.hora ASC'),
			'conditions' => array(
				'OR' => array(
					'Calendario.data LIKE' => date('Y-m').'-%',
					'Calendario.data_fim LIKE' => date('Y-m').'-%',
				),
			),
			'contain' => array(),
		));*/

		$calendarios = $this->Calendario->find('all', $params);
		
		//Se é ajax, não traz o layout
		if ($this->RequestHandler->isAjax()  ){
			$this->layout = null;
			$this->helpers[] = 'Calendar';
			$this->set('calendarios', $calendarios);
			$this->set('calendario_ano', $filtro_ano);
			$this->set('calendario_mes', $filtro_mes);
			
			$this->render('/elements/calendario_home');
			return;
		}

		if(empty($calendarios)){
			$this->Session->setFlash(__('Nenhum item foi encontrado neste calendário!', true),'default',array('class'=>'message_error'));
			$this->render('/pages/embreve');
			return;
			
		}
		

		//$this->set('title_for_layout', 'Informações / Calendário');

		$this->set('filtro_tipos', $this->Calendario->options['tipos']);
		$this->set('filtro_anos',$this->Calendario->getAnos());
		
		$this->set(compact('calendarios', 'filtro_ano', 'filtro_mes'));
	}
}