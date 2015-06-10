<div class="filtro"><?php
	/*
	echo '<span>Notícias de: ';
	foreach($noticias_anos AS $ano){
		$url = array('controller' => 'noticias_listar', 'action' => $ano,) + $this->params['named'];
		$options = array(
			'class' => (!empty($filtro_ano) && $ano == $filtro_ano ? 'active' : false),
		);
		
		echo $this->Html->link($ano, $url, $options);
		echo ' &nbsp;';
	}
	echo '</span>';
	*/
	?><ul class="meses"><?php
	
		if(empty($filtro_ano))$filtro_ano = date('Y');
		//Faz links da barra
		foreach($this->Formatacao->meses AS $key=>$mes){
			$key++;
			if($key < 10)$key = '0'.$key;
			$url = array('controller' => 'noticias_listar', 'action' => $filtro_ano, $key) + $this->params['named'];
			
			?><li <?php if(!empty($filtro_mes) && $key == (int)$filtro_mes)echo 'class="active"';?>><?php 
			
				if($filtro_ano.'-'.$key > date('Y-m')){
					echo $this->Html->link(substr($mes, 0, 3),'#', array('class' => 'nolink'));
				}else{
					echo $this->Html->link(substr($mes, 0, 3), $url);
				}
			?></li><?php
		}
	?></ul>
</div>