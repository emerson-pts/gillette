<?php
class GaleriasController extends AppController {

	var $name = 'Galerias';

	function download(){
		$file = implode(DS, $this->params['pass']);
		$file_without_extension = preg_replace('/^(.*)\.[^\.]+$/', '\1', $file);
		
		$this->view = 'Media';
		$params = array(
			'id' => end($this->params['pass']),
			'name' => basename($file_without_extension).'-'.basename(dirname($file_without_extension)),
			'download' => true,
			'extension' => strtolower(preg_replace('/^.*\./', '', $file)),
			'path' => APP . 'webroot' . DS . dirname($file) . DS,
		);
		$this->set($params);
	}
	
	function index(){
		//Abre o álbum se foi solicitado
		$galeria_atual['Galeria'] = array();

		//Faz com que o modelo retorne o fullpath com o caminho utilizando o friendly_url
		$this->Galeria->afterFindGetfullpath = array('friendly_url');

		
		//lê informações da galeria atual
		//Se enviou mais de 1 parametro na url, então tem alguma galeira aberta
		
		
		
		$this->set('galeria_atual',$galeria_atual);
		

		$this->Galeria->bindModel(array(
			'hasOne' => array(
				'GaleriaPreview' => array(
					'limit' => 1,
					'className' => 'GaleriaArquivo',
				),
			),
		));
		
		/*$params = array(
			'conditions' => array('Galeria.parent_id' => (isset($galeria_atual['Galeria']['id']) ? $galeria_atual['Galeria']['id'] : null)),
			'contain' 	=> array('GaleriaPreview','Subgaleria',),
			'order' 	=> array('Galeria.lft ASC'),
			'group'=> 'Galeria.id',
			'limit'	=> 12,
		);
		
	
		//$galerias=$this->Galeria->find('all', $params);

		$this->paginate['Galeria'] = $params;
		
		$galerias = $this->paginate('Galeria');*/
		
			$this->paginate = array(
			'limit' => 12,
			//'conditions' => array('Galeria.parent_id' => (isset($galeria_atual['Galeria']['id']) ? $galeria_atual['Galeria']['id'] : null),),
			'contain' 	=> array('GaleriaPreview','Subgaleria',),
			//'contain' => false,
			'order' 	=> array('Galeria.lft ASC'),
			'group'=> 'Galeria.id',

			);
			$galerias = $this->paginate('Galeria');
		/*
		foreach($galerias AS $key=>$value){
//			$galerias[$key]['Galeria']['fullpath'] = $this->Galeria->getfullpath($value['Galeria']['id'], '-');
			
			//Se a galeria não tem imagem de capa e não tem fotos diretamente, então tenta achar uma imagem de preview em uma subgaleria
			if(empty($value['Galeria']['imagem_capa']) && empty($value['GaleriaPreview']['arquivo'])){
				
				//Carrega todas subgalerias desta galeria independente da profundidade
				if(!$subgalerias = $this->Galeria->children($value['Galeria']['id']))continue;
				
				if($subgaleria = $this->Galeria->GaleriaArquivo->find('first', array(
					'conditions' => array(
						'GaleriaArquivo.galeria_id' => Set::extract('/Galeria/id', $subgalerias),
						//'GaleriaArquivo.arquivo !=' => '',
					),
					'contain' 	=> false,
					'order' 	=> array('GaleriaArquivo.id DESC')
				))){
					$galerias[$key]['GaleriaPreview'] = $subgaleria['GaleriaArquivo'];
				}
			}
		}
		*/
		
		$this->set(compact('galerias', 'galeria_atual'));	
	}
	
	function detalhe($friendly_url=null){
		//Abre o álbum se foi solicitado
		if(!empty($friendly_url)){
			$this->Galeria->afterFindGetfullpath = true;
			if(!$galeria_atual=$this->Galeria->find('first', array('contain' => array('GaleriaArquivo'), 'conditions' => array('Galeria.friendly_url' => $friendly_url)))){
				$this->Session->setFlash(__('Ops! Álbum não encontrado.', true),'default',array('class'=>'message_error'));
				$this->redirect('/');
			}
			
//			$galeria_atual['Galeria']['fullpath'] = $this->Galeria->getfullpath($galeria_atual['Galeria']['id'], '-');
			$galeria_atual['Galeria']['parent'] = $this->Galeria->find('first', array('contain' => false, 'conditions' => array('Galeria.id' => $galeria_atual['Galeria']['parent_id'])));
			
		}
		
		$this->set('galeria_atual',$galeria_atual);
		
	}
	
}