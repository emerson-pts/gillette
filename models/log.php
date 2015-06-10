<?php
class Log extends AppModel {

	var $name = 'Log';
	var $order = 'Log.created DESC';

	var $belongsTo = 'Usuario';
	
	function afterFind($results){
		$results = parent::afterFind($results);
		foreach($results AS $key=>$value){
			if(isset($value['Log']['action'])){
				switch($results[$key]['Log']['action']){
					case 'add':
						$results[$key]['Log']['action_formatada'] = 'Inclusão';
						break;

					case 'edit':
						$results[$key]['Log']['action_formatada'] = 'Alteração';
						break;
						
					case 'delete':
						$results[$key]['Log']['action_formatada'] = 'Remoção';
						break;

					default:
						$results[$key]['Log']['action_formatada'] = $results[$key]['Log']['action'];
						break;
				}
			}
			
			$results[$key]['Log']['change_array'] = $results[$key]['Log']['change_formatada'] = array();
			if(preg_match_all('/(\w+?) \((.*?)\) => \((.*?)\)(?:$|,)/s', $value['Log']['change'], $matches, PREG_SET_ORDER)){
				foreach($matches AS $match){
					list($search, $field, $from, $to) = $match;
					$results[$key]['Log']['change_array'][] = array(
						'field' => $field,
						'from' 	=> $from,
						'to'	=> $to,
					);
					$results[$key]['Log']['change_formatada'][] = "\"<span class=\"log_campo\">$field</span>\" de \"<span class=\"log_de\">".htmlentities($from, $flags = ENT_COMPAT, $charset = 'UTF-8')."</span>\" para \"<span class=\"log_para\">".htmlentities($to, $flags = ENT_COMPAT, $charset = 'UTF-8')."</span>\".";
				}
			}
		}
		return $results;
	}
}
