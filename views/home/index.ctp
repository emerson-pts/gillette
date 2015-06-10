<?php
echo $this->Html->css(array('vitrine',), $rel = null,array('inline'=>false));
echo $this->Html->script(array('jquery.dimensions','jquery.easing','jquery.jslidernews'),array('inline'=>false));?>
<div class="content_top"></div>
<div class="wrap">
	<div class="content_middle vitrine_new">
	<?php echo $this->Element('vitrine');?>
	</div>
</div><!-- wrap -->
<div class="wrap">
	<div id="box-noticias-home">
		<?php
			//Destaques
				$noticias_destaque = array_slice($noticias, 0, 4);
				$noticias_lista = array_slice($noticias, 3);
		
				$i=1;
				foreach ($noticias_destaque AS $key => $noticia){
					//echo '<pre>';print_r($noticia);echo '</pre>';
					//Link na imagem
					?>
						<div class="noticia-destaque<?php if($i%4==0){ ?>-last<?php } ?>">
							<a href="<?php echo $this->Html->url($noticia["Noticia"]["link"]); ?>">
							<?php
							//DATA
							echo $this->Html->div('noticia-data',$noticia["Noticia"]["data_noticia_data"].' &agrave;s '.$noticia['Noticia']["data_noticia_hora"]);
							//BORDA DA IMAGEM
							//echo $this->Html->div('noticia-img-border','');
							//IMAGEM
							echo $this->Html->link(
								$this->Html->image(
									(!empty($noticia['Noticia']['image']))?array('controller'=>'thumbs','?'=>array('src'=> $noticia['Noticia']['image'],'size'=>'216*129','crop'=>'216x129')):'img-default/imagem-default-peq.jpg',
									array("alt" =>$noticia['Noticia']['titulo'],"width"=>"216","height"=>"129","class"=>"noticia-home-img")
								),
								$noticia['Noticia']['link'],
								array('escape' => false)
							);
							?>
							<a href="<?php echo $this->Html->url($noticia["Noticia"]["link"]); ?>">	
							<?php
							//TITULO
							echo $this->Html->tag('h3',$noticia['Noticia']['titulo']);
							echo $this->Html->tag('br','');
							//PREVIEW
							echo $this->Html->tag('h4',
								$this->Text->truncate(
									!empty($noticia['Noticia']['olho'])?$noticia['Noticia']['olho']:(!empty($noticia['Noticia']['conteudo_preview']) ? $noticia['Noticia']['conteudo_preview'] : $noticia['Noticia']['conteudo']),
									200,
									array('ending' => '...','exact' => false,'html'=>false)
								)
							);
							?>
							</a>
							<?php
							echo $this->Html->tag('br','');
							
							?></a>
						</div>
						<?php //.noticia-destaque
					$i++;
				}
				?><br class="clear" />
	</div><!-- box-noticias-home -->
</div><!-- wrap -->
	    <br />
	<div class="linha"></div>
	</div>
</div>