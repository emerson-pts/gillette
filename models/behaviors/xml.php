<?php
//App::import('Vendor','cs-phpxml/xmlparserclass');

//Converte um campo com o sufixo xml para xml no beforesave e transforme em array no afterfind
class XmlBehavior extends ModelBehavior {

	//Transforma string XML em array
	function array2xml($model, $array, $tab = 0, $current_node = ''){
		$return = '';

		foreach($array AS $key=>$value){
			if(is_numeric($key))$key = $current_node.'_'.$key;
			$return .= str_repeat("\t",$tab).'<'.$key.'>';
			if (!is_array($value)){
				$return .= $value;
			}else{
				$return .= "\r\n".$this->array2xml($model, $value, $tab + 1, $key).str_repeat("\t",$tab);
			}
			$return .= '</'.$key.">\r\n";
		}
		return $return;
	}

	function xml2array($model, $data){
		if (preg_match_all('/<([^>]+)>(.*?)<\/\1>/s', $data, $matches, PREG_SET_ORDER)){
			$return = array();
			$matches_count = count($matches);
			foreach($matches AS $match){
				$xml2array = $this->xml2array($model, $match[2]);
				if (isset($return[$match[1]]))$match[1] = $match[1].'-'.md5(microtime());
				$return[$match[1]] = $xml2array;
			}
		}else
			$return = $data;
	
		return $return;
	}
}