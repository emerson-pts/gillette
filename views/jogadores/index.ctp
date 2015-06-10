<?php echo $this->set('title_for_layout', 'Quem joga'); ?>
<?php
$jogadores_tipos = array(
	'destaques'		=> Set::extract('/Jogador[destaque=1]', $jogadores),
	'convidados'	=> Set::extract('/Jogador[tipo=convidado]', $jogadores),
	'alternate' 	=> Set::extract('/Jogador[tipo=alternate]', $jogadores),
	'principal' 	=> Set::extract('/Jogador[tipo=principal]', $jogadores),
);
?>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.noticia-destaque.quem_joga-last:not(:last)').after('<div class="clear"></div><hr />');
	});
</script>
<div class="wrap">
	<div class="content_top"></div>
	<div class="content_middle">
	    	<div class="title-paginas">
				<div class="linha-title-paginas"></div>
					<center>
						<span>
							<?php echo $this->Html->image("deco_title_left.jpg", array("alt" => ""));?>
							<?php echo $this->params['sitemap']['Sitemap']['label']; ?>
							<?php echo $this->Html->image("deco_title_right.jpg", array("alt" => ""));?>
		
						</span>
					</center>
			</div>
		<?php if(!empty($jogadores_tipos['destaques'])):?>
	    <div class="wrap quem-joga">
	    	<?php foreach($jogadores_tipos['destaques'] AS $key=>$jogador):?>
			<div class="noticia-destaque quem_joga<?php if(($key+1) % 4 == 0){echo '-last';}?>">
	            <div class="noticia-img-border"></div>
				<?php
				//A imagem abaixo deve ser cropada para ter 281x154px
					echo $this->Html->image(
						(!empty($jogador['Jogador']['image']))?array('controller'=>'thumbs','?'=>array('src'=>$jogador['Jogador']['image'],'size'=>'216*129','crop'=>'216x129')):'img-default/imagem-default-peq.jpg',
						array('width'=>'216','height'=>'129')
					)
				?>
				<h3><?php echo $this->Html->image('/img/flags/'.strtolower($jogador['Jogador']['pais']).'.png', array('style'=>'margin-bottom:3px;')).'  '.$jogador['Jogador']['nome'];?></h3>
				<div class="txt-padrao"><?php echo $jogador['Jogador']['ficha'];?></div>
			</div>
	    	<?php endforeach;?>
			<br class="clear" />
			<br />
		</div>
		<?php endif;?>
	</div>
	<div class="content_bottom"></div>
<div class="linha"></div>
</div>