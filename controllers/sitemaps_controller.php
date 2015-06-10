<?php
class SitemapsController extends AppController {

	var $name = 'Sitemaps';
	
	function index(){
		//Pega somente os argumentos numéricos e procura o caminho
		
		$path = Set::extract('{n}',$this->passedArgs);
		while(!($sitemap = $this->Sitemap->findPath($path, 'friendly_url')) && !empty($path)){
			array_pop($path);
		}

		if(!empty($sitemap)){
			$params = Set::extract('{[^0-9]+}',$this->passedArgs);
			foreach($params AS $key=>$value){
				$params[$key] = $key.':'.$value;
			}

			//Se a rota do caminho atual foi definida			
			if(!empty($sitemap['Sitemap']['route'])){
				$route = $sitemap['Sitemap']['route'];
			}
			//Se é categoria principal, e tem subpágina...
			else if(empty($sitemap['Sitemap']['parent_id']) && !empty($sitemap['children'][0])){
				$this->redirect('/'.$sitemap['Sitemap']['friendly_url'].'/'.$sitemap['children'][0]['Sitemap']['friendly_url']);
			}
			//Caso contrário
			else{
				$route = 'paginas/'.$sitemap['Sitemap']['friendly_url'];
				
				//$this->Session->setFlash(__('Ops! Página não encontrada', true),'default',array('class'=>'message_error'));
				//$this->redirect('/');
			}

			//Se a rota começa com /, então redireciona
			if(preg_match('/^(http|\/)/', $route))
				//$this->redirect($route.'/'.implode('/', $params));
				$this->redirect($route);


			App::import('Vendor', 'WebjumpDispatcher');
			$Dispatcher = new WebjumpDispatcher();
			$Dispatcher->dispatch($route.'/'.implode('/', $params), array('sitemap' => $sitemap, 'originalArgs' => array('here' => $this->here, 'passedArgs' => $this->passedArgs, 'params' => $this->params)));
			exit;
		}
		else{
			$this->Session->setFlash(__('Ops! Página não encontrada ou caminho inválido.', true),'default',array('class'=>'message_error'));
			$this->render('/pages/blank');
			//$this->redirect('/');
		}
	}
}