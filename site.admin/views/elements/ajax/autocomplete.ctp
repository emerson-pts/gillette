<?php 
foreach ($results As $k=>$v) { 
	$value=''; 
	foreach ($fields As $i =>$j) {
		if($j == 'itemLabel')
			$value .= '"'.str_replace('"','\\"',(isset($v[0]['itemLabel']) ? $v[0]['itemLabel'] : $v[$model]['itemLabel'])).'",';
		else
			$value .= '"'.str_replace('"','\\"',$v[$model][$j]).'",'; 			
	} 
	//;exit;
	echo "<li><a href='javascript://Selecionar' onclick='set_".$input_id."(".substr($value,0, -1).")'>".$this->Text->highlight((isset($v[0]['itemLabel']) ? $v[0]['itemLabel'] : $v[$model]['itemLabel']), $query, array('html' => true))."</a></li>";
} 
