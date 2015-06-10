<?php
//Faz com que os endereços de página e ordenação gerados pelo paginator incluam as variáveis submetidas na url
$paginator->options(array('url' => $this->passedArgs));

?><div class="col-left">
	<div class="category-top">
		<div class="category">Multimídia</div>
		<dir class="category-intro">Confira <?php echo $multimidias_tipos[$multimidia_tipo];?> do Super Kart Brasil</dir>
	</div><?php 
		
	//chama o filtro de multimidias
	echo $this->Element('multimidias/filtro_ultimas');
		
	//Se não tem notícias
	if(empty($multimidias)){
		echo '<div class="message_error">'.__('Nenhum conteúdo foi encontrado.', true).'</div>';
	}else{
		//MULTIMIDIAS
		foreach($multimidias as $key=>$multimidia){
		//MULTIMIDIA AREA
		?><div class="video-area"><?php
			//TITULO
			echo $this->Html->tag('h2',$multimidia["Multimidia"]["titulo"],array('escape'=>'true'));
			//EMBED
			if(!empty($multimidia["Multimidia"]["conteudo"]))
				echo $multimidia["Multimidia"]["conteudo"];
		?></div><?php
		}
	}
			
	//BOTÃO VER MAIS
	/*echo $this->Html->div('noticias-mais-btn',
		$this->Html->link('+ multimidias', 'javascript:void(0)', array('target' => '_self','class'=>'paging_more')),
		null,
		false
	);*/	
	
	//PAGINAÇÃO
	echo $this->Html->div('paging_more',$this->Element('paginacao'),null,false);
?></div>