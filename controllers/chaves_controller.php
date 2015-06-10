<?php
class ChavesController extends AppController {

	var $name = 'Chaves';
	var $uses = array('Jogo');	
	function index($chave = null){
		//Verifica se enviou a chave pelo sitemap
		if(!empty($this->params['originalArgs']['params']['pass'])){
			//Se o caminho atual, for encontrado no sitemap
			$current_path = $this->params['originalArgs']['params']['pass'];
			$current_depth = 0;//Profundidade máxima para tentar recuperar
			//Tenta encontrar o caminho atual no sitemap
			while($current_depth < 20 && !$var_sitemap = $this->Sitemap->findPath($current_path, 'friendly_url')){
				//Se não for encontrado, vai um nível acima
				$current_depth++;
				$current_path = array_slice($current_path, 0, -1);
			}
			
			//Se a rota definida
			if(!strstr($var_sitemap['Sitemap']['route'], '/')){
				$args = array('chave');
			}
			
			//Se estava acessando o mesmo endereço definido no sitemap com caminhos adicionais (argumentos)...
			if(!empty($current_depth)){
				// Pega somente os argumentos enviados depois do caminho cadastrado no sitemap
				$originalArgs_passedArgs = array_slice($this->params['originalArgs']['passedArgs'],-$current_depth);
				//Recupera os valores cujos índices foram encontrados nos args 
				$intersect_values = array_intersect_key($originalArgs_passedArgs, $args);
	
				//Recupera os nomes dos índices cujos valores foram cruzados 
				$intersect_keys = array_intersect_key($args, $intersect_values);
				
				//Combina os índices e os valores
				$overwrite_args = array_combine($intersect_keys, $intersect_values);
				
				//Passa valores para $nome_indice
				extract($overwrite_args);
			}
		}

		//Lista chaves
		$chaves =  $this->Jogo->children(null, true);
		$this->set('chaves', $chaves);

		//Se não solicitou nenhuma chave
		if(empty($chave)){
			$chave = $chaves[0]['Jogo']['friendly_url'];
		}
			
		//Lê a árvore de chaves
		//Verifica id da chave atual
		if(!$chave_atual=$this->Jogo->find('first', array('conditions' => array('Jogo.friendly_url' => $chave)))){
			$this->Session->setFlash(__('Ops! Grupo não encontrado.', true),'default',array('class'=>'message_error'));
			$this->redirect();
			return;
		}			
		$rodadas = $this->Jogo->chaves($chave_atual['Jogo']['id']);
				
		$this->set(compact('chaves', 'chave_atual', 'rodadas'));	
	}
	
}