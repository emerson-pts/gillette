<?php
// Send Header
$xls->setHeader((empty($filename) ? 'arquivo' : $filename).'.xls');

$xls->BOF();

$x = 0;
foreach($fields AS $key=>$label){
	$xls->writeLabel(0, $x, utf8_decode(preg_replace('/\[(number|text)\]$/', '', $label)));
	$x++;
}

foreach($result AS $y=>$value){
	$y++;
	$x = 0;
	foreach($fields AS $key=>$label){
		$current_value = $value;

		//Se o campo contém pontos, então processa o nome do campo como o valor de uma array multidimensional
		if(strstr($key, '.')){
			while(strstr($key, '.')){
				if(!isset($current_value[preg_replace('/\..*$/', '', $key)])){
					$current_value = array();
					break;
				}
				
				$current_value = $current_value[preg_replace('/\..*$/', '', $key)];
				$key = preg_replace('/^[^\.]+\./', '', $key);
			}
		}
		if(isset($current_value[$key]) && is_array($current_value[$key]))$current_value[$key] = implode("\r\n", $current_value[$key]);
		
		if(!isset($current_value[$key])){
			if(preg_match('/\[numeric\]$/', $label)){
				$xls->writeNumber($y, $x, 0);
			}else{
				$xls->writeLabel($y, $x, '');
			}
		}else if((is_numeric($current_value[$key]) && !preg_match('/\[text\]$/', $label))|| preg_match('/\[number\]$/', $label)){
			$xls->writeNumber($y, $x, utf8_decode($current_value[$key]));
		}else{
			$xls->writeLabel($y, $x, utf8_decode($current_value[$key]));
		}
		$x++;
	}
}

$xls->EOF();
exit();