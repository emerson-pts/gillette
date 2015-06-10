<?php
class WebjumpHelper extends Helper {
	function return_bytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
	
		return $val;
	}

	//Formata tag de keyword
	function keyword( $value, $conf = array()){
		$conf += array(
			'label' 	=> 'nome', 
			'color' 	=> 'color', 
			'tag_open' 	=> '<span class="keyword %s-keyword">', 
			'tag_close'	=> '</span>',
		);
		if(empty($value) || !is_array($value) || !array_key_exists($conf['color'], $value) || !array_key_exists($conf['label'], $value))return false;
		return sprintf($conf['tag_open'], $value[$conf['color']]) . $value[$conf['label']] . $conf['tag_close'];
	}
	
	function keywords( $values, $conf = array()){
		$conf += array(
			'empty'		=> '<small>-Não informado-</small>',
		);
		
		$return = array();
		foreach($values AS $key=>$value){
			if(empty($value)){
				$return[$key] = $conf['empty'];
			}else{
				$return[$key] = self::keyword($value, $conf);
			}
		}
		$return = implode(' ', array_unique($return));
		if(empty($return)){
			return $conf['empty'];
		}else{
			return $return;
		}
	}
}