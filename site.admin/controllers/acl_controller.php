<?php
class AclController extends AppController {

	var $name = 'Acl';
	var $uses = null;

	function _getAcoByPath($full_path){
		$parent_id = null; 
		$full_path = explode('/', $full_path);
		foreach($full_path AS $partial_path){
			if($new_parent_id = $this->Acl->Aco->find('list',array('conditions' => array('alias' => $partial_path, 'parent_id' => $parent_id )))){
				$parent_id = current($new_parent_id);
			}else{
				return false;
			}
		}
		return $parent_id;
	}
	
	function set_permission($model, $id){
		if(empty($model) || empty($id)){
			$msg = 'O modelo e/ou o código não foram informados!';

		}else{
			
			App::import('Model', $model);
			$modelo = new $model;
			$modelo->id = $id;

			$modelo->setUserData($this->Auth->user());
			$modelo->setUserIp($_SERVER['REMOTE_ADDR']);
	
			//Limpa cache da seção
			$this->Session->delete('AclHelper_check');
	
			//Permite
			if($this->data['Acl']['allowdeny'] == 'allow'){
				$msg = $this->Acl->allow($modelo, $this->data['Acl']['full_path']);
			}else{
			//Nega
				$this->Acl->Aro->bindModel(array('hasOne' => array('ArosAco')));
				if (!$aro_id = $this->Acl->Aro->find('list', array('conditions' => array('model'=> $model, 'foreign_key' => $id), 'contain' => false))){
					$msg = 'O objeto de requisição da autorização não foi localizado!';
				}else if(!$aco_id = $this->_getAcoByPath($this->data['Acl']['full_path'])){
					$msg = 'O objeto do controle de permissão não foi localizado!';
				}
				//Se não encontra o controle da permissão, faz a negação convencional
				else if(!$aro_aco_id = $this->Acl->Aro->ArosAco->find('list', array('conditions' => array('aro_id' => current($aro_id), 'aco_id' => $aco_id)))){
					$msg = $this->Acl->deny($modelo, $this->data['Acl']['full_path']);
				}else if (true !== ($result = $this->Acl->Aro->ArosAco->delete(current($aro_aco_id)))) {
					$msg = 'Não foi possível remover a permissão! '.$result;
				}else{
					$msg = true;
				}
				$this->Acl->Aro->unbindModel(array('hasOne' => array('ArosAco')));
			}
		}
		
		if ( $this->RequestHandler->isAjax()  ){
			if($msg === true){
				//verifica quais itens da lista podem ter sofrido alteração
				$acos = $this->Acl->Aco->find('threaded');
				$aco_tree = $this->_aco_tree($acos, array($model => array('id' => $id)));
				
				foreach($aco_tree AS $key=>$value){
					if(!preg_match('/^'.preg_quote($this->data['Acl']['full_path'], '/').'/', $key))unset($aco_tree[$key]);
				}
									
				$this->set('data', array('msg' => $msg, 'allowdeny' => $this->data['Acl']['allowdeny'], 'aco_tree' => $aco_tree));
			}else{
				$this->set('data', array('msg' => $msg,));
			}
			$this->render('/elements/json');
		}else{
			$this->Session->setFlash(($msg === true ? 'Permissão alterada com sucesso!' : $msg),'default',array('class'=>'message_'.($msg === true ? 'success' : 'error')));
			$this->set('content','');
			$this->render('/elements/blank');
		}
	}

	function index($model, $id){
		if(empty($model) || empty($id)){
			$this->Session->setFlash(__('Erro no acesso a lista de controle de acessos! O modelo e/ou o código não foram informados!', true),'default',array('class'=>'message_error'));
			$this->redirect('/');
		}
		
		App::import('Model', $model);
		$modelo = new $model;
		$this->set('current_aro', $modelo->read('nome', $id));
		
		//Recupera os ids do Aro (requester)
		if(!$aro = $this->Acl->Aro->node(array($model => array('id' => $id)))){
			//Se não encontrou, então cria um
			$data = array(
				'model' => $model,
				'foreign_key' => $id,
			);
			$this->Acl->Aro->create();
			if($this->Acl->Aro->save($data)){
				$this->Session->setFlash(__('O pedido de acesso da lista de controle foi criado com sucesso!', true),'default',array('class'=>'message_success'));
				$aro = $this->Acl->Aro->node(array($model => array('id' => $id)));
			}else{
				$this->Session->setFlash(__('Ocorreu um erro na criação do pedido de acesso da lista de controle!', true),'default',array('class'=>'message_error'));
				$this->redirect('/'.$model.'s');
			}
		}

		$aro_ids = array();
		foreach($aro AS $key=>$value){
			$aro_ids[] = $value['Aro']['id'];
		}
		
		$acos = $this->Acl->Aco->find('threaded');
		
		$aco_tree = $this->_aco_tree($acos, array($model => array('id' => $id)));
		
		$controller_traduzido = Configure::read('controllerTraduzido');
		
		foreach($aco_tree AS $key=>$value){
			$full_path = explode('/', $value['full_path']);
			foreach($full_path AS $path_key=>$path_value){
				if(isset($controller_traduzido[$path_value])){
					$full_path[$path_key] = $controller_traduzido[$path_value];
				}
			}
			$aco_tree[$key]['full_path_traduzido'] = implode(' / ', $full_path);
			
		}
		$this->set('aco_tree', $aco_tree);
	}

	function build_acl($model, $id) {
/*
		if (!Configure::read('debug')) {
			return $this->_stop();
		}
*/
		$log = array();

		//Limpa cache da seção
		$this->Session->delete('AclHelper_check');

		$aco =& $this->Acl->Aco;
		$aco->setUserData($this->Auth->user());
		$aco->setUserIp($_SERVER['REMOTE_ADDR']);

		$root = $aco->node('controllers');
		if (!$root) {
			$aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
			$root = $aco->save();
			$root['Aco']['id'] = $aco->id; 
			$log[] = 'Created Aco node for controllers';
		} else {
			$root = $root[0];
		}   

		App::import('Core', 'File');
		$Controllers = Configure::listObjects('controller');
		$appIndex = array_search('App', $Controllers);
		if ($appIndex !== false ) {
			unset($Controllers[$appIndex]);
		}
		$baseMethods = get_class_methods('Controller');
		$baseMethods[] = 'buildAcl';

		$Plugins = $this->_getPluginControllerNames();
		$Controllers = array_merge($Controllers, $Plugins);

		// look at each controller in app/controllers
		foreach ($Controllers as $ctrlName) {
			$methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

			// Do all Plugins First
			if ($this->_isPlugin($ctrlName)){
				$pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
				if (!$pluginNode) {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
					$pluginNode = $aco->save();
					$pluginNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
				}
			}
			// find / make controller node
			$controllerNode = $aco->node('controllers/'.$ctrlName);
			if (!$controllerNode) {
				if ($this->_isPlugin($ctrlName)){
					$pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
					$aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
				} else {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $ctrlName;
				}
			} else {
				$controllerNode = $controllerNode[0];
			}

			//clean the methods. to remove those in Controller and private actions.
			if(is_array($methods)){
				foreach ($methods as $k => $method) {
					if (strpos($method, '_', 0) === 0) {
						unset($methods[$k]);
						continue;
					}
					if (in_array($method, $baseMethods)) {
						unset($methods[$k]);
						continue;
					}
					$methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
					if (!$methodNode) {
						$aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
						$methodNode = $aco->save();
						$log[] = 'Created Aco node for '. $method;
					}
				}
			}
		}
		if(count($log)>0) {
			debug($log);
		}
		
		$this->Session->setFlash(__('Lista de controles de acesso atualizada com sucesso!', true),'default',array('class'=>'message_success'));
		$this->redirect(array('action' => 'index', $model, $id));
	}

	function _getClassMethods($ctrlName = null) {
		App::import('Controller', $ctrlName);
		if (strlen(strstr($ctrlName, '.')) > 0) {
			// plugin's controller
			$num = strpos($ctrlName, '.');
			$ctrlName = substr($ctrlName, $num+1);
		}
		$ctrlclass = $ctrlName . 'Controller';
		$methods = get_class_methods($ctrlclass);

		// Add scaffold defaults if scaffolds are being used
		$properties = get_class_vars($ctrlclass);
		if (is_array($properties) && array_key_exists('scaffold',$properties)) {
			if($properties['scaffold'] == 'admin') {
				$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
			} else if(!empty($properties['scaffold'])){//Eu coloquei o !empty para não acrescentar essas ações se o scaffold está desativado
				$methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
			}
		}
		return $methods;
	}

	function _isPlugin($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) > 1) {
			return true;
		} else {
			return false;
		}
	}

	function _getPluginControllerPath($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0] . '.' . $arr[1];
		} else {
			return $arr[0];
		}
	}

	function _getPluginName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0];
		} else {
			return false;
		}
	}

	function _getPluginControllerName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[1];
		} else {
			return false;
		}
	}

/**
 * Get the names of the plugin controllers ...
 * 
 * This function will get an array of the plugin controller names, and
 * also makes sure the controllers are available for us to get the 
 * method names by doing an App::import for each plugin controller.
 *
 * @return array of plugin names.
 *
 */
	function _getPluginControllerNames() {
		App::import('Core', 'File', 'Folder');
		$paths = Configure::getInstance();
		$folder =& new Folder();
		$folder->cd(APP . 'plugins');

		// Get the list of plugins
		$Plugins = $folder->read();
		$Plugins = $Plugins[0];
		$arr = array();

		// Loop through the plugins
		foreach($Plugins as $pluginName) {
			// Change directory to the plugin
			$didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
			// Get a list of the files that have a file name that ends
			// with controller.php
			$files = $folder->findRecursive('.*_controller\.php');

			// Loop through the controllers we found in the plugins directory
			foreach($files as $fileName) {
				// Get the base file name
				$file = basename($fileName);

				// Get the controller name
				$file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
				if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
					if (!App::import('Controller', $pluginName.'.'.$file)) {
						debug('Error importing '.$file.' for plugin '.$pluginName);
					} else {
						/// Now prepend the Plugin name ...
						// This is required to allow us to fetch the method names.
						$arr[] = Inflector::humanize($pluginName) . "/" . $file;
					}
				}
			}
		}
		return $arr;
	}
	
	function _aco_tree($acos, $aro, $full_path = '', $current_controller = false){
		$aco_tree = array();
		foreach($acos AS $key=>$value){
			$current_full_path = $full_path . $value['Aco']['alias'];
			
			//Pega liberações forçadas no controller, pois estas sobrepões o do ACL
			if(preg_match('#^([^/]+)/([^/]+)?/?$#', $full_path, $match)){
				
				//É o próprio controler
				if(empty($match[2]))
					$match[2] = $value['Aco']['alias'];
				
				//Se mudou o controller atualiza os dados
				if($current_controller != $match[2]){
					App::import('Controller', $match[2]);
					$controller_name = $match[2].'Controller';
					
					if(!class_exists($controller_name)){
						$this->Acl->Aco->delete($value['Aco']['id']);
						continue;
					}
					$current_controller = new $controller_name;
					if(!empty($current_controller->allowedActions))
						$current_controller->allowedActions = ($current_controller->allowedActions[0] == '*' ? $current_controller->allowedActions : array_merge($current_controller->allowedActions, (!empty($current_controller->appAllowedActions) ? $current_controller->appAllowedActions : array())));
					else if(!empty($current_controller->appAllowedActions))
						$current_controller->allowedActions = $current_controller->appAllowedActions;
					else
						$current_controller->allowedActions = false;
				}
			}
			
			$aco_tree[$current_full_path] = array(
				'id' => $value['Aco']['id'],
				'alias' => $value['Aco']['alias'],
				'full_path' => $current_full_path,
				'authorized' => $this->Acl->check($aro, $current_full_path),
				'authorized_controller' => (
					$current_controller != false && !empty($current_controller->allowedActions) && (in_array($value['Aco']['alias'], $current_controller->allowedActions) || in_array('*', $current_controller->allowedActions)) 
					?
					1
					:
					''
				),
				
			);
			$aco_tree += $this->_aco_tree($value['children'], $aro, $current_full_path.'/', $current_controller);
		}
		ksort($aco_tree);
		return $aco_tree;
	}
}