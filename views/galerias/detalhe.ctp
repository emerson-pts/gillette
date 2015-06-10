<?php // echo '<pre>';print_r($this->params);echo '</pre>'; ?>
<?php echo $this->set('title_for_layout', 'Galeria - '.$galeria_atual['Galeria']['label']); ?>
<div class="wrap">
	<div class="content_top"></div>
	<div class="content_middle">
	<div class="title-paginas">
		<div class="linha-title-paginas"></div>
			<center>
				<span>
					<?php echo $this->Html->image("deco_title_left.jpg", array("alt" => ""));?>
					<?php __('Galerias'); ?>
					<?php echo $this->Html->image("deco_title_right.jpg", array("alt" => ""));?>

				</span>
			</center>
			
	</div>

	<?php
	//GALERIA INTERNA - DOWNLOADS
	if(!empty($galeria_atual['Galeria'])): 
	?><div id="galeria-download">
		<div class="linha-titulo-galeria-ver">
			<br />
			<div class="noticia-data-ver"><?php echo $galeria_atual['Galeria']['data'];?></div>
			<h2><?php echo $galeria_atual['Galeria']['label'];?></h2>
			<br />
			<div class="btn-voltar"><?php echo $this->Html->link(__('Voltar', true), '/galerias/');?></div>
			<br />
		</div>
		<?php
		//Se não tem foto e não tem sub-album
		if(empty($galeria_atual['GaleriaArquivo']) && empty($galerias)):
		?>	
			<p class="erro"><?php __('Nenhuma imagem está disponível neste álbum!'); ?></p>
		<?php
		endif;

		$i=1;
		foreach($galeria_atual['GaleriaArquivo'] as $galeriaArquivo):
			?><div class="noticia-destaque<?php if(($i%4)==0){ ?> last<?php } ?>">
				<div class="gal-img">
					<?php echo $this->Html->link('<span>Download</span>','/download/'.$galeriaArquivo['arquivo'],array('class'=>'gal-down', 'escape' => false));?>
					<?php echo $this->Html->link(
						$this->Html->image(
							array('controller'=>'thumbs','?'=>array('src'=>$galeriaArquivo["arquivo"],'size'=>'216*129','crop'=>'216x129')),
							array("alt" => $galeriaArquivo["legenda"],'width'=>'216','height'=>'129',)
						),
						array('controller'=>'thumbs','?'=>array('src'=>$galeriaArquivo["arquivo"],'size'=>'w800')),
						array('title' => $galeriaArquivo['legenda'], 'id' => 'galeria_item_'.$galeriaArquivo['id'], 'rel'=>'prettyPhoto[galeria]','escape'=>false)
				);
				?></div>
				<br class="clear" />
			</div><?php //.noticia-destaque
		$i++;
		endforeach;
		?>
		<br class="clear" />
	</div><?php //galeria-download
	endif;
	?>
	</div>
<div class="content_bottom"></div>
<?php
echo $this->Html->css(array('prettyPhoto',), $rel = null,array('inline'=>false));
echo $this->Html->script(array('jquery.prettyPhoto'),array('inline'=>false));?>
<script language="javascript">
jQuery(document).ready(function() {
	jQuery("a[rel^='prettyPhoto'], a.prettyphoto, a.lightbox, a.prettyPhoto").prettyPhoto({show_title: false});
});
</script>
<br />
<br />
<div class="linha"></div>
</div>