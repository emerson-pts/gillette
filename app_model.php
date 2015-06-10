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
	var $actsAs = array('DateFormatter','Containable','EnumSet',);
		
	function beforeSave(){
		foreach($columns = $this->getColumnTypes() AS $field=>$type){
			if (preg_match('/^data_(cadastro|alteracao)$/',$field) && preg_match('/^(date|datetime)$/',$type) && !isset($this->data[$this->alias][$field]))
				$this->data[$this->alias][$field] = date('Y-m-d'.($type == 'datetime' ? ' H:i:s' : ''));
		}
		return true;
	}
	
	function invalidate($field, $value = true) {
		return parent::invalidate($field, __($value, true));
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
