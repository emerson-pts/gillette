<?php
/**
 * Logs saves and deletes of any model
 * 
 * Requires the following to work as intended :
 * 
 * - "Log" model ( empty but for a order variable [created DESC]
 * - "logs" table with these fields required :
 *     - id			[int]			: 
 *     - title 		[string] 		: automagically filled with the display field of the model that was modified.
 * 	   - created	[date/datetime] : filled by cake in normal way
 * 
 * - actsAs = array("Logable"); on models that should be logged
 * 
 * Optional extra table fields for the "logs" table :
 * 
 * - "description" 	[string] : Fill with a descriptive text of what, who and to which model/row :  
 * 								"Contact "John Smith"(34) added by User "Administrator"(1).
 * 
 * or if u want more detail, add any combination of the following :
 * 
 * - "model"    	[string] : automagically filled with the class name of the model that generated the activity.
 * - "model_id" 	[int]	 : automagically filled with the primary key of the model that was modified.
 * - "action"   	[string] : automagically filled with what action is made (add/edit/delete) 
 * - "user_id"  	[int]    : populated with the supplied user info. (May be renamed. See bellow.)
 * - "change"   	[string] : depending on setting either : 
 * 							[name (alek) => (Alek), age (28) => (29)] or [name, age]
 * 
 * - "version_id"	[int]	 : cooperates with RevisionBehavior to link the the shadow table (thus linking to old data)
 * 
 * Remember that Logable behavior needs to be added after RevisionBehavior. In fact, just put it last to be safe.
 * 
 * Optionally register what user was responisble for the activity :
 * 
 * - Supply configuration only if defaults are wrong. Example given with defaults :
 * 
 * class Apple extends AppModel {
 * 		var $name = 'Apple';
 * 		var $actsAs = array('Logable' => array('userModel' => 'User', 'userKey' => 'user_id'));
 *  [..]
 * 
 * - In AppController (or single controller if only needed once) add these lines to beforeFilter : 
 * 
 *   	if (sizeof($this->uses) && $this->{$this->modelClass}->Behaviors->attached('Logable')) {
 *			$this->{$this->modelClass}->setUserData($this->activeUser);
 *		}
 *
 *   Where "$activeUser" should be an array in the standard format for the User model used :
 * 
 *   $activeUser = array( $UserModel->alias => array( $UserModel->primaryKey => 123, $UserModel->displayField => 'Alexander'));
 *   // any other key is just ignored by this behaviour.
 * 
 * @author Alexander Morland (alexander#maritimecolours.no)
 * @co-author Eskil Mjelva Saatvedt
 * @co-author Ronny Vindenes
 * @co-author Carl Erik Fyllingen
 * @category Behavior
 * @version 2.1
 * @modified 27.jan 2008 by alexander
 */

class LogableBehavior extends ModelBehavior 
{
	public $user = NULL;
	public $UserModel = FALSE;
    public $settings = array();
	public $defaults = array(
			'userModel' => 'User',
			'userKey' => 'user_id',
			'change' => 'list',
			'description_ids' => TRUE,
			'skip' => array(),
			'ignore' => array(),
			'disable_behavior' => array(),//Disable this behaviors on read old data. Ex. date formaters
			'ignore_models' => array(),
		);
	/**
	 * Cake called intializer
	 * Config options are :
	 *    userModel 		: 'User'. Class name of the user model you want to use (User by default), if you want to save User in log
	 *    userKey   		: 'user_id'. The field for saving the user to (user_id by default).
	 * 	  change    		: 'list' > [name, age]. Set to 'full' for [name (alek) => (Alek), age (28) => (29)]
	 * 	  description_ids 	: TRUE. Set to FALSE to not include model id and user id in the title field
	 *    skip  			: array(). String array of actions to not log
	 *
	 * @param Object $Model
	 * @param array $config
	 */
	function setup(&$Model, $config = array()) {		
		if (!is_array($config)) {
			$config = array();
		}	
		$this->settings[$Model->alias] = array_merge($this->defaults, $config);
		$this->settings[$Model->alias]['ignore'][] = $Model->primaryKey; 
				

		//Se é o modelo de dados log, desabilita o behavior logable
		if($Model->alias == 'Log' || in_array($Model->alias, $this->settings[$Model->alias]['ignore_models'])){
			$Model->Behaviors->disable('Logable');
			return false;
		}

		//Ajusta configuração skip, caso ela tenha sido setada como uma string
		if(!is_array($this->settings[$Model->alias]['skip']) && !empty($this->settings[$Model->alias]['skip']))$this->settings[$Model->alias]['skip'] = array($this->settings[$Model->alias]['skip']);

		App::import('model','Log');
		$this->Log = new Log();
		if ($this->settings[$Model->alias]['userModel'] != $Model->alias) {
			if (App::import('model',$this->settings[$Model->alias]['userModel'])) {
	        	$this->UserModel = new $this->settings[$Model->alias]['userModel']();
	        }
		} else {
			$this->UserModel = $Model;
		}            
	}
	
	function settings(&$Model) {
		return $this->settings[$Model->alias];
	}
	

	//Retorna todo o log relacionado a um resultado
	function findAllLog(&$Model, $results, $model_filter, $params = array()){
		$defaults = array(
			 'model' => NULL,
			 'action' => NULL,
			 'order' => 'Log.created DESC',
			 $this->settings[$Model->alias]['userKey'] => NULL,
			 'conditions' => array(),
			 'fields' => array(),
			 'limit' => 50,
		);
		$params = array_merge($defaults, $params);
		$options = array('order' => $params['order'], 'conditions' => $params['conditions'], 'fields' => $params['fields'], 'limit' => $params['limit']);

    	if ($params['action'] && isset($this->Log->_schema['action'])) {
    		$options['conditions']['action'] = $params['action'];
    	}     	
		if ($params[ $this->settings[$Model->alias]['userKey'] ] && $this->UserModel && is_numeric($params[ $this->settings[$Model->alias]['userKey'] ])) {
			$options['conditions'][$this->settings[$Model->alias]['userKey']] = $params[ $this->settings[$Model->alias]['userKey'] ];
		}

		if(count($results) == 1 && isset($results[0]) && $results[0] == true){
			$options['conditions']['model'] = $model_filter;
		}else{
			$options['conditions']['OR'] = $this->_conditions($results, $model_filter, (!empty($params['model']) ? $params['model'] : $Model->alias));
		}
    	return $this->Log->find('all',$options);
	}

	function _conditions($results, $model_filter, $current_model = null){
		$conditions = array();
		
		foreach($results AS $key=>$value){
			if(is_array($value)){
				$conditions = array_merge($conditions, $this->_conditions($value, $model_filter, (is_numeric($key) ? $current_model : $key)));
			}else if($key == 'id' && in_array($current_model, $model_filter) ){
				$conditions[] = array('model' => $current_model, 'model_id' => $value);
			}
		}
		return $conditions;
	}

	/**
	 * Useful for getting logs for a model, takes params to narrow find. 
	 * This method can actually also be used to find logs for all models or
	 * even another model. Using no params will return all activities for
	 * the models it is called from.
	 *
	 * Possible params :
	 * 'model' 		: mixed  (NULL) String with className, NULL to get current or FALSE to get everything
	 * 'action' 	: string (NULL) String with action (add/edit/delete), NULL gets all
	 * 'order' 		: string ('created DESC') String with custom order
	 * 'conditions  : array  (array()) Add custom conditions
	 * 'model_id'	: int	 (NULL) Add a int 
	 * 
	 * (remember to use your own user key if you're not using 'user_id')
	 * 'user_id' 	: int 	 (NULL) Defaults to all users, supply id if you want for only one User
	 * 
	 * @param Object $Model
	 * @param array $params
	 * @return array
	 */
	
	function findLog(&$Model, $params = array()) {
		$defaults = array(
			 'model' => NULL,
			 'action' => NULL,
			 'order' => 'created DESC',
			 $this->settings[$Model->alias]['userKey'] => NULL,
			 'conditions' => array(),
			 'model_id' => NULL,
			 'fields' => array(),
			 'limit' => 50,
		);
		$params = array_merge($defaults, $params);
		$options = array('order' => $params['order'], 'conditions' => $params['conditions'], 'fields' => $params['fields'], 'limit' => $params['limit']);
		if ($params['model'] === NULL) {
			$params['model'] = $Model->alias;
		}
		if ($params['model']) {
	    	if (isset($this->Log->_schema['model'])) {
	    		$options['conditions']['model'] = $params['model'];
	    	} elseif (isset($this->Log->_schema['description'])) {    		
	    		$options['conditions']['description LIKE '] = $params['model'].'%';
	    	} else {
	    		return FALSE;
	    	}
		}
    	if ($params['action'] && isset($this->Log->_schema['action'])) {
    		$options['conditions']['action'] = $params['action'];
    	}     	
		if ($params[ $this->settings[$Model->alias]['userKey'] ] && $this->UserModel && is_numeric($params[ $this->settings[$Model->alias]['userKey'] ])) {
			$options['conditions'][$this->settings[$Model->alias]['userKey']] = $params[ $this->settings[$Model->alias]['userKey'] ];
		}
		if ($params['model_id'] && is_numeric($params['model_id'])) {
			$options['conditions']['model_id'] = $params['model_id'];
		}
    	return $this->Log->find('all',$options);
	}
	
	/**
	 * Get list of actions for one user.
	 * Params for getting (one line) activity descriptions 
	 * and/or for just one model 
	 *
	 * @example $this->Model->findUserActions(301,array('model' => 'BookTest'));
	 * @example $this->Model->findUserActions(301,array('events' => true));
	 * @example $this->Model->findUserActions(301,array('fields' => array('id','model'),'model' => 'BookTest');
	 * @param Object $Model
	 * @param int $user_id
	 * @param array $params
	 * @return array
	 */
	function findUserActions(&$Model, $user_id, $params = array()) {
		if (!$this->UserModel) {
			return NULL;
		}
		// if logged in user is asking for her own log, use the data we allready have
		if ( isset($this->user) 
			 && isset($this->user[$this->UserModel->alias][$this->UserModel->primaryKey]) 
			 && $user_id == $this->user[$this->UserModel->alias][$this->UserModel->primaryKey] 
			 && isset($this->user[$this->UserModel->alias][$this->UserModel->displayField]) ) {
			$username = $this->user[$this->UserModel->alias][$this->UserModel->displayField];
		} else {
			$this->UserModel->recursive = -1;
			$user = $this->UserModel->find(array($this->UserModel->primaryKey => $user_id));
			$username = $user[$this->UserModel->alias][$this->UserModel->displayField];
		}
		$fields = array();
		if (isset($params['fields'])) {
			if (is_array($params['fields'])) {
				$fields = $params['fields'];
			} else {
				$fields = array($params['fields']);
			}
		}
		$conditions = array($this->settings[$Model->alias]['userKey'] => $user_id);
		if (isset($params['model'])) {
			$conditions['model'] = $params['model'];
		}
		$data = $this->Log->find('all', array(
			'conditions' => $conditions,
			'recursive' => -1,
			'fields' => $fields
		));
		if (! isset($params['events']) || (isset($params['events']) && $params['events'] == false)) {
			return $data;
		}
		$result = array();
		foreach ($data as $key => $row) {$one = $row['Log'];
			$result[$key]['Log']['id'] = $one['id'];
			$result[$key]['Log']['event'] = $username;
			// have all the detail models and change as list : 
			if (isset($one['model']) && isset($one['action']) && isset($one['change']) && isset($one['model_id'])) {
				 if ($one['action'] == 'edit') {
				 	$result[$key]['Log']['event'] .= ' alterou '.$one['change'].' do '.low($one['model']).'(id '.$one['model_id'].')';
				 	//	' at '.$one['created']; 
				 } elseif ($one['action'] == 'add') {
				 	$result[$key]['Log']['event'] .= ' incluiu o '.low($one['model']).'(id '.$one['model_id'].')';
				 } elseif ($one['action'] == 'delete') {
				 	$result[$key]['Log']['event'] .= ' removeu o '.low($one['model']).'(id '.$one['model_id'].')';
				 }
					 	
			} elseif ( isset($one['model']) && isset($one['action'])  && isset($one['model_id']) ) { // have model,model_id and action
                 if ($one['action'] == 'edit') {
				 	$result[$key]['Log']['event'] .= ' alterou '.low($one['model']).'(id '.$one['model_id'].')';
				 	//	' at '.$one['created']; 
				 } elseif ($one['action'] == 'add') {
				 	$result[$key]['Log']['event'] .= ' incluiu o '.low($one['model']).'(id '.$one['model_id'].')';
				 } elseif ($one['action'] == 'delete') {
				 	$result[$key]['Log']['event'] .= ' removeu o '.low($one['model']).'(id '.$one['model_id'].')';
				 }
			} else { // only description field exist
                $result[$key]['Log']['event'] = $one['description'];
			}
				
		}
		return $result;
	}
    /**
     * Use this to supply a model with the data of the logged in User.
     * Intended to be called in AppController::beforeFilter like this :
     *   
 	 *   	if ($this->{$this->modelClass}->Behaviors->attached('Logable')) {
 	 *			$this->{$this->modelClass}->setUserData($activeUser);/
 	 *		}
     *
     * The $userData array is expected to look like the result of a 
     * User::find(array('id'=>123));
     * 
     * @param Object $Model
     * @param array $userData
     */
	function setUserData(&$Model, $userData = null) {
		if ($userData) {
			$this->user = $userData;
		}
	}
		
	/**
	 * Used for logging custom actions that arent crud, like login or download.
	 *
	 * @example $this->Boat->customLog('ship', 66, array('title' => 'Titanic heads out'));
	 * @param Object $Model
	 * @param string $action name of action that is taking place (dont use the crud ones)
	 * @param int $id  id of the logged item (ie model_id in logs table)
	 * @param array $values optional other values for your logs table
	 */
	function customLog(&$Model, $action, $id, $values = array()) {		
		$logData['Log'] = $values;
		/** @todo clean up $logData */
		if (isset($this->Log->_schema['model_id']) && is_numeric($id)) {
			$logData['Log']['model_id'] = $id;
		}
		$title = NULL;
		if (isset($values['title'])) {
    		$title = $values['title']; 
    		unset($logData['Log']['title']);
		}
    	$logData['Log']['action'] = $action;
    	$this->_saveLog($Model, $logData, $title);
	}
	
	function clearUserData(&$Model) {
		$this->user = NULL;
	}
	
	function setUserIp(&$Model, $userIP = null) {
		$this->userIP = $userIP;
	}
	

	
	function beforeDelete(&$Model) {
		if (isset($this->settings[$Model->alias]['skip']['delete']) && $this->settings[$Model->alias]['skip']['delete']) {
			return true;
		}
		$Model->recursive = -1;
		$Model->read();
		return true;
	}
	
	function afterDelete(&$Model) {
		if (isset($this->settings[$Model->alias]['skip']['delete']) && $this->settings[$Model->alias]['skip']['delete']) {
			return true;
		}
		$logData = array();
		if (isset($this->Log->_schema['description'])) {
			$logData['Log']['description'] = $Model->alias;
			if (isset($Model->data[$Model->alias][$Model->displayField]) && $Model->displayField != $Model->primaryKey) {
				$logData['Log']['description'] .= ' "'.$Model->data[$Model->alias][$Model->displayField].'"';
			}
			if ($this->settings[$Model->alias]['description_ids']) {
				$logData['Log']['description'] .= ' ('.$Model->id.') ';
			}
			$logData['Log']['description'] .= __('removido',TRUE);
		}
    	$logData['Log']['action'] = 'delete'; 	
    	$this->_saveLog($Model, $logData);
	}
    
	function beforeSave(&$Model) {
        if (isset($this->Log->_schema['change']) && $Model->id) {


			//Desabilita behaviors solicitados
			foreach($this->settings[$Model->alias]['disable_behavior'] AS $disable_behavior){
				$Model->Behaviors->disable($disable_behavior);
			}
			
			$contain = array();
			//Tem hasandbelongstomany, então recupera o original deles também
			if(!empty($Model->hasAndBelongsToMany)){
				if(!is_array($Model->hasAndBelongsToMany) && isset($Model->data[$Model->hasAndBelongsToMany])){
					$contain[] = $Model->hasAndBelongsToMany;
				}else{
					foreach($Model->hasAndBelongsToMany AS $habtm=>$habtm_config){
						if(isset($Model->data[$habtm]))$contain[] = $habtm;
					}
				}
			}
			
			//Seta o relacionamento habtm para verificação posterior
			$this->habtm = $contain;
			
			$Model->calledFromBehavior = get_class($this);
			
			$this->old = $Model->find('first',array('conditions'=>array($Model->alias.'.'.$Model->primaryKey => $Model->id),/*'recursive'=>-1,*/ 'contain' => $contain));

			unset($Model->calledFromBehavior);

  			//Reabilita behaviors desabilitados anteriormente
			foreach($this->settings[$Model->alias]['disable_behavior'] AS $disable_behavior){
				$Model->Behaviors->enable($disable_behavior);
			}

        }
        return true;
	}
	
    function afterSave(&$Model,$created) {
		if (isset($this->settings[$Model->alias]['skip']['add']) && $this->settings[$Model->alias]['skip']['add'] && $created) {
			return true;
		} elseif (isset($this->settings[$Model->alias]['skip']['edit']) && $this->settings[$Model->alias]['skip']['edit'] && !$created) {
			return true;
		}

		$keys = array_keys($Model->data[$Model->alias]);
		$diff = array_diff($keys,$this->settings[$Model->alias]['ignore']);
				
		if (sizeof($diff) == 0 && empty($Model->logableAction)) {
			return false;
		}
     	if ($Model->id) {
    		$id = $Model->id;
    	} elseif ($Model->insertId) {
    		$id = $Model->insertId;
    	}     	
        if (isset($this->Log->_schema['model_id'])) {
   			$logData['Log']['model_id'] = $id;
    	}
		if (isset($this->Log->_schema['description'])) {		
	    	$logData['Log']['description'] = $Model->alias.' ';
		 	if (isset($Model->data[$Model->alias][$Model->displayField]) && $Model->displayField != $Model->primaryKey) {
		 		$logData['Log']['description'] .= '"'.$Model->data[$Model->alias][$Model->displayField].'" ';
		 	}
	    	
	        if ($this->settings[$Model->alias]['description_ids']) {
	        	$logData['Log']['description'] .= '('.$id.') ';
	        }
										
	    	if ($created) {
	    		$logData['Log']['description'] .= __('incluído',TRUE);
	    	} else {
	    		$logData['Log']['description'] .= __('atualizado',TRUE);   
	    	}  
		}     
		if (isset($this->Log->_schema['action'])) {					
	    	if ($created) {
	    		$logData['Log']['action'] = 'add';
	    	} else { 
	    		$logData['Log']['action'] = 'edit'; 		
	    	}  
			
		}
    	if (isset($this->Log->_schema['change'])) {
    		$logData['Log']['change'] = '';
    		$db_fields = array_keys($Model->_schema);
    		$changed_fields = array();
    		foreach ($Model->data[$Model->alias] as $key => $value) {
    			if (isset($Model->data[$Model->alias][$Model->primaryKey]) && !empty($this->old) && isset($this->old[$Model->alias][$key])) {
    				$old = $this->old[$Model->alias][$key];
    			} else {
    				$old = '';
    			}
    			if ($key != 'modified' 
	    			&& !in_array($key, $this->settings[$Model->alias]['ignore'])
	    			&& $value != $old && in_array($key,$db_fields) ) 
	    			{
	    				if ($this->settings[$Model->alias]['change'] == 'full') {
	    					$changed_fields[] = $key . ' ('.$old.') => ('.$value.')';
	    				} else {
	    					$changed_fields[] = $key;	
	    				}    				
	    			}
    		}
    		
    		//Checa alterações em HABTM
    		if(!empty($this->habtm)){
				foreach($this->habtm AS $habtm){
					$old = $this->old[$habtm];
					
					$new_habtm = $Model->data[$habtm][$habtm];
					//Pega os ids do velho
					$old_habtm = array();
					foreach($old AS $key=>$value){
						$old_habtm[] = $value['id'];
					}
					
					if(is_array($new_habtm) && count($old_habtm) != 0){
						//Compara o velho com o novo
						$diff = array_diff($new_habtm, $old_habtm);
						if (sizeof($diff) != 0 || count($new_habtm) != count($old_habtm)){
							//Formata old 
							if ($this->settings[$Model->alias]['change'] == 'full') {
								//Usa o display_name do modelo HABTM para exibir as alterações
								$old_values = array();
								foreach($old AS $value){
									$old_values[$value[$Model->$habtm->primaryKey]] = $value[$Model->$habtm->displayField];
								}
								ksort($old_values);

								//Lê ids do novo, que não existiam no velho
								if(sizeof($diff) > 0)$habtm_data = $Model->$habtm->find('list',array('conditions'=>array('id' => $diff),'recursive'=>-1));
								
								$new_values = array();
								foreach($new_habtm AS $value){
									$new_values[$value] = (isset($old_values[$value]) ? $old_values[$value] : $habtm_data[$value]);
								}
								ksort($new_values);
								
								foreach(array('old_values', 'new_values') AS $array){
									if(count(${$array}) == 0)
										${$array} = '';
									else if(count(${$array}) == 1)
										${$array} = current(${$array});
									else
										${$array} = implode(', ',array_slice(${$array}, 0, -1)). ' ' . __('e', true). ' ' . end(${$array});
								}
								
								$changed_fields[] = $habtm. ' ('.$old_values.') => ('.$new_values.')';
							} else {
								$changed_fields[] = $habtm;	
							}    				
						}
					}
				}
    		}
    		
    		$changes = sizeof($changed_fields);
    		if ($changes == 0) {
    			return true;
    		} 
    		$logData['Log']['change'] = implode(', ',$changed_fields);
    		$logData['Log']['changes'] = $changes;		
    	}  
    	$this->_saveLog($Model, $logData);
    }
    
    /**
     * Does the actual saving of the Log model. Also adds the special field if possible.
     * 
     * If model field in table, add the Model->alias
     * If action field is NOT in table, remove it from dataset
     * If the userKey field in table, add it to dataset
     * If userData is supplied to model, add it to the title 
     *
     * @param Object $Model
     * @param array $logData
     */
    function _saveLog(&$Model, $logData, $title = null) {  
    	if ($title !== NULL) {
    		$logData['Log']['title'] = $title;
    	} elseif ($Model->displayField == $Model->primaryKey) {
    		$logData['Log']['title'] = $Model->alias . ' ('. $Model->id.')';
    	} elseif (isset($Model->data[$Model->alias][$Model->displayField])) {
    		$logData['Log']['title'] = $Model->data[$Model->alias][$Model->displayField];
    	} else {
    		$Model->recursive = -1;
    		$Model->read(array($Model->displayField));
    		$logData['Log']['title'] = $Model->data[$Model->alias][$Model->displayField];
    	}
    		
    	if (isset($this->Log->_schema['model'])) {
    		$logData['Log']['model'] = $Model->alias;
    	}
    	
    	if (isset($this->Log->_schema['model_id']) && !isset($logData['Log']['model_id'])) {
    		if ($Model->id) {
    			$logData['Log']['model_id'] = $Model->id;
    		} elseif ($Model->insertId) {
    			$logData['Log']['model_id'] = $Model->insertId;
    		}     		
    	}
		
    	if (!isset($this->Log->_schema[ 'action' ])) {
    		unset($logData['Log']['action']);
    	} elseif (isset($Model->logableAction) && !empty($Model->logableAction)) {
    		$logData['Log']['action'] = implode(',',$Model->logableAction); // . ' ' . $logData['Log']['action'];
    		unset($Model->logableAction);
    	}
    	
    	if (isset($this->Log->_schema[ 'version_id' ]) && isset($Model->version_id)) {
    		$logData['Log']['version_id'] = $Model->version_id;
    		unset($Model->version_id);
    	}
    	
    	if (isset($this->Log->_schema[ 'ip' ]) && !empty($this->userIP)) {
    		$logData['Log']['ip'] = $this->userIP;
    	}
    	
    	if (isset($this->Log->_schema[ $this->settings[$Model->alias]['userKey'] ]) && $this->user) {
    		$logData['Log'][$this->settings[$Model->alias]['userKey']] = $this->user[$this->UserModel->alias][$this->UserModel->primaryKey];
    	}  	
    	
        if (isset($this->Log->_schema['description'])) {
        	if ($this->user && $this->UserModel) {
        		$logData['Log']['description'] .= ' pelo '.$this->settings[$Model->alias]['userModel'].' "'.
        				$this->user[$this->UserModel->alias][$this->UserModel->displayField].'"';
        		if ($this->settings[$Model->alias]['description_ids']) {
        			$logData['Log']['description'] .= ' ('.$this->user[$this->UserModel->alias][$this->UserModel->primaryKey].')';
        		}
    										
        	} else { 
        		// UserModel is active, but the data hasnt been set. Assume system action.
        		$logData['Log']['description'] .= ' pelo Sistema';
        	}
    		$logData['Log']['description'] .= '.';    		
    	} 	
    	
    	$this->Log->create();
    	$this->Log->save($logData,array('callbacks'=> false));
    }
}
