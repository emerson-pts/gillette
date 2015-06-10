<div style="white-space: nowrap; float: right;"><?php
$vendas=Configure::read('vendas');
ksort($vendas);
foreach(Configure::read('vendas') as $i=>$value){
	$key = key($value);
	$venda = current($value);
	if(!empty($venda)){	
		echo $this->Html->link($this->Html->image('vendas/'.$key.".jpg", array("alt" => "", "style" => ($i == 0 ? '' : "margin-left:19px"))),$venda,array('escape' => false,'target'=>'blank'));	
	}
}
?></div>