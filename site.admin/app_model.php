<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
class AppModel extends Model {
	var $actsAs = array('Lockable', 'DateFormatter','Containable','EnumSet',
		'Logable' =>  array(
			'userModel' => 'Usuario', 
			'userKey' => 'usuario_id', 
			'change' => 'full', // options are 'list' or 'full'
			'description_ids' => TRUE, // options are TRUE or FALSE
			'ignore' => array('data_cadastro','senha',),
			'skip' => 'add',
			'disable_behavior' => array('DateFormatter', 'Xml',),
			'ignore_models'	=> array('Aro', 'Sitemap', 'Galeria', /*'Jogo', /* 'Aco', 'ArosAco'*/),
		)
	);

	function beforeSave(){
		foreach($columns = $this->getColumnTypes() AS $field=>$type){
			if (preg_match('/^data_(cadastro|alteracao)$/',$field) && preg_match('/^(date|datetime)$/',$type) && !isset($this->data[$this->alias][$field]))
				$this->data[$this->alias][$field] = date('Y-m-d'.($type == 'datetime' ? ' H:i:s' : ''));
		}
		
		//Meio Upload - limpa caminho completo do campo dir
		if(isset($this->actsAs['MeioUpload'])){
			foreach($this->actsAs['MeioUpload'] AS $upload_field=>$upload_conf){
				if(!empty($this->data[$this->alias][$upload_field])){
					$this->data[$this->alias][$upload_field] = preg_replace('/^((?!webroot).)*\/webroot\//', '', $upload_conf['dir']).$this->data[$this->alias][$upload_field];
				}
			}
		}
		
		return true;
	}
	
	function afterSave(){
		$this->clearCache();
	}

	function afterDelete(){
		$this->clearCache();
	}
	
	function clearCache(){
		if($files = array_merge(
			glob(preg_replace('/\.admin/', '', CACHE).'views/*.*'), 
			glob(preg_replace('/\.admin/', '', CACHE).'*.*'),
			glob(CACHE.'views/*.*'), 
			glob(CACHE.'*.*'),
			glob(CACHE.'cake_*')
		)){
			foreach($files as $v)unlink($v);
		}
		return true;
	}
	
	function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
		$parameters = compact('conditions', 'recursive');
		if (isset($extra['group'])) {
			$parameters['fields'] = $extra['group'];
			if (is_string($parameters['fields'])) {
				// pagination with single GROUP BY field
				if (substr($parameters['fields'], 0, 9) != 'DISTINCT ') {
					$parameters['fields'] = 'DISTINCT ' . $parameters['fields'];
				}
				unset($extra['group']);
				$count = $this->find('count', array_merge($parameters, $extra));
			} else {
				// resort to inefficient method for multiple GROUP BY fields
				$count = $this->find('count', array_merge($parameters, $extra));
				$count = $this->getAffectedRows();
			}
		} else {
			// regular pagination
			$count = $this->find('count', array_merge($parameters, $extra));
		}
		return $count;
	}
	
	// just pulled out of thin air (i.e. untested)
	function validateAtLeastOne($data) {
		$args = func_get_args();  // will contain $data, 'myField2', 'myField3', ...
	
		foreach ($args as $name) {
			if (is_array($name)) {
				$name = current(array_keys($name));
			}
			if (!empty($this->data[$this->alias][$name])) {
				return true;
			}
		}
	
		return false;
	}
	
	function friendly_url_validate($field, $options = array(), $validate_options = null){
		$defaults = array('field' => null, 'key_to_return' => 'friendly_url');
		$options = Set::merge($defaults, $options);

		//Se enviou a url, ignora verificação - neste caso, acrescente a validação unique no campo friendly_url
		if(!empty($this->data[$this->alias][$options['key_to_return']])){
			$this->data[$this->alias][$options['key_to_return']] = strtolower(Inflector::slug($this->data[$this->alias][$options['key_to_return']],'-'));
			return true;
		}
		
		if(empty($options['field'])){
			$value = current($field);
		}else{
			foreach($options['field'] AS $key => $field){
				$options['field'][$key] = Set::extract($field, $this->data);
			}
			$value = implode(' ', $options['field']);
		}

		if(!$friendly_url = $this->friendly_url($value)){
			return false;
		}
	
		$this->data[$this->alias][$options['key_to_return']] = $friendly_url;
		return true;
	}
	
	function friendly_url($value, $id = null){
		if(empty($id))$id = $this->id;
		//Troca acentos, caracteres especiais por hifen
 		$friendly_url = strtolower(Inflector::slug($value,'-'));
		$sufix = 0;
		
		if(empty($friendly_url)){
			$friendly_url = __('sem-titulo', true);
		}
		
		$friendly_url_valid = $friendly_url.(empty($sufix) ? '' : '-'.$sufix);
		
		while($sufix < 9999 && 0 != ($count = $this->find('count', array('contain' => array(), 'conditions' => array('friendly_url' => $friendly_url_valid) + (!empty($id) ? array('id !=' => $id) : array()))))){
			$sufix++;
			$friendly_url_valid = $friendly_url.(empty($sufix) ? '' : '-'.$sufix);
		}
		
		if($sufix == 9999 && !empty($count)){
			return false;
		}
		
		return $friendly_url_valid;
	}
	
	function afterFind($results, $primary = true){
		//Primary é false quando a array do resultado é enviada no formato 
		//	array('campo1' => 'valor1', 'campo2' => 'valor2')
		//	ou array(0 => array('Model' => array('campo1' => 'valor1', 'campo2' => 'valor2')), 1 => array('Model' => array('campo1' => 'valor1', 'campo2' => 'valor2')))
		if(/*!$primary ||*/ !is_array(current($results))){

			$results = $this->afterFindExecute($results);
		}
		//Se foi enviada no formato array('Model' => array('campo1' => 'valor1', 'campo2' => 'valor2'))
		else if (array_key_exists($this->alias, $results)){
			$results[$this->alias] = $this->afterFindExecute($results[$this->alias]);
		}
		//Caso contrário está no formato array(0 => array('Model' ...)=> array('campo1' => 'valor1', 'campo2' => 'valor2')))
		else{
			foreach ($results as $key => $val) {
				if(isset($val[$this->alias])){
					$results[$key][$this->alias] = $this->afterFindExecute($val[$this->alias]);
				}
			}
		}
		//Se está setada a variável $this->afterFindGetfullpath...
		if(!empty($this->afterFindGetfullpath)){
			//Faz backup da configuração
			$configGetfullpath = $this->afterFindGetfullpath;

			//reseta a variável 
			$this->afterFindGetfullpath = false;
			foreach($results AS $key=>$value){
				if(!isset($value[$this->alias]['id']))break;
				
				if(is_array($configGetfullpath)){
					foreach($configGetfullpath AS $label){
						$results[$key][$this->alias]['fullpath'.($label == $this->displayField ? '' : '_'.$label)] = $this->getfullpath($value[$this->alias]['id'], $separator = '/', $label);
					}
				}else{
					$results[$key][$this->alias]['fullpath'] = $this->getfullpath($value[$this->alias]['id']);
				}
			}
			$this->afterFindGetfullpath = $configGetfullpath;
		}
		return $results;
	}
		
	//Funções padrão de afterFind
	function afterFindExecute($result){
		return $result;
	}
}
