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
	<?php
	//GALERIAS GERAL	
	if(!empty($galerias)):
		if(!empty($galeria_atual['GaleriaArquivo'])):
		?>
			<br />
			<h2><?php __('Veja também'); ?></h2>
		<?php
		endif;

		$i=1;
		foreach($galerias as $galeria):
			//CAPA DA GALERIA
			?><div class="noticia-destaque<?php if(($i%4)==0){ ?>-last<?php } ?> galerias"><?php
				//BORDA DA IMAGEM
				echo $this->Html->link(
					$this->Html->div('noticia-img-border','').
					$this->Html->div('noticia-data',$galeria['Galeria']['data'].'&nbsp;').
					$this->Html->image(
						//SE nao Tiver CAPA Mostra uma das imagems internas ou imagem default
						!empty($galeria["Galeria"]['imagem_capa'])?array('controller'=>'thumbs','?'=>array('src'=>$galeria["Galeria"]['imagem_capa'],'size'=>'216*129','crop'=>'216x129')):(!empty($galeria['GaleriaPreview']['arquivo'])?array('controller'=>'thumbs','?'=>array('src'=>$galeria['GaleriaPreview']['arquivo'],'size'=>'216*129','crop'=>'216x129')):'img-default/imagem-default-peq.jpg'),
						array("alt" =>$galeria["Galeria"]["label"],"width"=>"216","height"=>"129",'style'=>'float:left',)
					).
					$this->Html->tag('h3',
							$this->Text->truncate($galeria['Galeria']['label'],23,array('ending' => '','exact' => false,'html'=>false))
						)
				,
					//url
					'/galeria/'.$galeria["Galeria"]['fullpath_friendly_url'],
					array('escape' => false,)
				);
				//
			?></div><?php //.noticia-destaque
			$i++;
		//FIM loop galerias
		endforeach;
		?>
		<div class="clear"></div>
		<hr class="galerias" />
		<div class="wrap paginacao">
			<?php 
				$result_galerias = count($galerias);
				//print_r($result_galerias);
				//if($result_galerias > 12){
					echo $this->element('paginacao');
				//} 
			 ?>
		</div>
		<?php
	endif;
?></div>
<div class="content_bottom"></div>
<div class="linha" style="background-color:#c65727 !important"></div>
</div>