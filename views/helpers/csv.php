<?php
class CsvHelper extends Helper {
	//formata campo_csv
	function format_csv($value,$size,$type,$error_die=1,$msg_add=''){
	   //type N number -- T string
	   settype($value,"string");
	   if (is_array($size)){
			$casas_decimais = $size[1];
			$size = $size[0];
	   }

	   if (strlen($value)>$size AND $error_die == 1){
		  die("<script>alert('Dado {$value} maior que o permitido ({$size})".$msg_add."');</script>Dado {$value} maior que o permitido ({$size})".$msg_add.DBG_GetBacktrace());
	   }else if (strlen($value)>$size){
		  $value = substr($value,0,$size);
	   }else if (strlen($value)<$size AND $type == "T"){
		  $value .= str_repeat(" ",$size-strlen($value));
	   }else if (strlen($value)<$size AND $type == "N"){
		  $value = str_repeat("0",$size-strlen($value)).$value;
	   }else if (preg_match('/^N(\.|,)([0-9]+)?$/',$type, $match)){
			$value = format_csv(number_format($value, (isset($match[2]) && is_numeric($match[2]) ? $match[2] : 2), $match[1], ''),$size,'N',$error_die,$msg_add);
	   }
	   return $value;
	}
}