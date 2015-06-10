<div class="filtro">
	<span><?php echo $multimidias_tipos[$multimidia_tipo];?> de: <?php
		foreach($multimidias_anos AS $ano){
			$url = array('controller' => Inflector::pluralize($multimidia_tipo), 'action' => $ano, /* descomente para selecionar o mês atual ao clicar no ano (empty($filtro_mes) ? date('m') : $filtro_mes)*/) + $this->params['named'];
			$options = array(
				'class' => (!empty($filtro_ano) && $ano == $filtro_ano ? 'active' : false),
			);
			
			echo $this->Html->link($ano, $url, $options);
			echo ' &nbsp;';
		}
	?>
	</span>
	<ul class="meses">
		<?php
		if(empty($filtro_ano))$filtro_ano = date('Y');
		//Faz links da barra
		foreach($this->Formatacao->meses AS $key=>$mes){
			$key++;
			if($key < 10)$key = '0'.$key;
			$url = array('controller' => Inflector::pluralize($multimidia_tipo), 'action' => $filtro_ano, $key) + $this->params['named'];
			?><li <?php if(!empty($filtro_mes) && $key == (int)$filtro_mes)echo 'class="active"';?>>
				<?php 
				if($filtro_ano.'-'.$key > date('Y-m')){
					echo $this->Html->link(substr($mes, 0, 3),'#', array('class' => 'nolink'));
				}else{
					echo $this->Html->link(substr($mes, 0, 3), $url);
				}
				?>
			</li>
		<?php
		}
		?>
	</ul>
</div>
<div class="filtro">
	<span>Filtrar por categoria:</span>
	<ul class="categoria">
			<li <?php if(empty($this->params['named']['categoria']))echo 'class="active"';?>><?php echo $this->Html->link('&bull;Todas', array('controller' => Inflector::pluralize($multimidia_tipo), 'action' => $filtro_ano, $filtro_mes,) , array('escape' => false));?></li>
		<?php foreach($multimidias_categorias AS $key=>$value):?>
			<li <?php if(!empty($this->params['named']['categoria']) && $key == $this->params['named']['categoria'])echo 'class="active"';?>><?php echo $this->Html->link('&bull;'.$value, array('controller' => Inflector::pluralize($multimidia_tipo), 'action' => $filtro_ano, $filtro_mes,'categoria' => $key) , array('escape' => false));?></li>
		<?php endforeach; ?>
	</ul>
</div>