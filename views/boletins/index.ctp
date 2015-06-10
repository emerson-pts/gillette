<?php
echo $this->Html->css(array('boletim'), $rel = null,array('inline'=>false));
?><div class="content_top"></div>
<div class="content_middle">
	<div class="linha-titulo-home"><?php
		echo $this->Html->tag('h2',(isset($menu_ativo[0]['Sitemap']['label']))?$menu_ativo[0]['Sitemap']['label']:'Boletim');
		echo $this->Html->link($this->Html->image("ticketsforfun.jpg", array("alt" => "", "style" => "float:right")), "/", array('escape' => false));
		echo $this->Html->tag('br','',array('class'=>'clear'));
	?></div><br /><br /><?php

	if(!empty($boletim)){
	
		echo '<div class="noticia-data">'.$boletim['Boletim']['data'].'</div>';
		echo '<h5>'.$boletim['Boletim']['titulo'].'</h5>';
		echo $boletim['Boletim']['conteudo'];
	}
	
	if(!empty($boletins)){
		echo $this->Html->div('linha-titulo-full','<h2>OUTROS BOLETINS</h2>');
		echo '<div id="boletins">';
			$i=1;
			foreach($boletins as $boletim){
				echo '<a href="'.$this->Html->url('/'.$menu_ativo[0]['Sitemap']['friendly_url'].'/'.$boletim['Boletim']['friendly_url']).'"'.(($i%4==0)?' class="last"':'').'>';
						echo '<small>'.$boletim['Boletim']['data'].'</small>';
						echo '<h3>'.$boletim['Boletim']['titulo'].'</h3>';
						echo $this->Html->div('miniatura',$this->Html->image(array('controller'=>'thumbs','?'=>array('src'=>$boletim['Boletim']['image'],'size'=>'72*122','crop'=>'72x122'))));
				echo '</a>';
				$i++;
			}
		echo '</div>'; //#Boletins
	}
?>
<div class="clear"></div>
</div>
<div class="content_bottom"></div>