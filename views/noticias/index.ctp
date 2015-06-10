<div class="wrap">
	<?php
	
	//Faz com que os endereços de página e ordenação gerados pelo paginator incluam as variáveis submetidas na url
	$paginator->options(array('url' => $this->passedArgs));
	
	?>
	<div class="content_middle">
		<div class="title-paginas">
				<div class="linha-title-paginas linha-title-pag-not"></div>
					<center>
						<span>
							<?php echo $this->Html->image("deco_title_left_not.jpg", array("alt" => ""));?>
							<?php __('Notícias'); ?>
							<?php echo $this->Html->image("deco_title_right_not.jpg", array("alt" => ""));?>
	
						</span>
					</center>
				
		</div>

	    
	    
	    <?php
	 
		//Se não tem notícias
		if(empty($noticias)):
			echo $this->Html->div('message_error',__('Nenhum conteúdo foi encontrado.', true));
			$noticias_lista = $noticias;
		else:
			//Destaques
			$noticias_destaque = array_slice($noticias, 0, 4);
			$noticias_lista = array_slice($noticias, 4);
			 //echo '<pre>';print_r($noticias_lista);echo '</pre>';
			?><div><?php
			$i=1;
			foreach ($noticias_destaque AS $key => $noticia){
				//Link na imagem
				?><div class="noticia-destaque<?php if($i%4==0){ ?>-last<?php } ?>">
					<a href="<?php echo $this->Html->url($noticia["Noticia"]["link"]); ?>">
					<?php
					//DATA
					echo $this->Html->div('noticia-data',$noticia["Noticia"]["data_noticia_data"].' &agrave;s '.$noticia['Noticia']["data_noticia_hora"]);
					//BORDA DA IMAGEM
					//echo $this->Html->div('noticia-img-border','');
					//IMAGEM
					echo $this->Html->link(
						$this->Html->image(
							(!empty($noticia['Noticia']['image']))?array('controller'=>'thumbs','?'=>array('src'=>$noticia['Noticia']['image'],'size'=>'216*129','crop'=>'216*129')):'img-default/imagem-default-peq.jpg', //teste.png  
							array("alt" =>$noticia['Noticia']['titulo'],"width"=>"216","height"=>"129",)
						),
						$noticia['Noticia']['link'],
						array('escape' => false)
					);
					?>
					<a href="<?php echo $this->Html->url($noticia["Noticia"]["link"]); ?>">
						<?php
						//TITULO
						echo $this->Html->tag('h3',$this->Text->truncate($noticia['Noticia']['titulo'],
						60,
						array('ending' => false,'exact' => false)));
						echo $this->Html->tag('br','');
						//PREVIEW
						echo $this->Html->tag('h4',
							$this->Text->truncate(
								!empty($noticia['Noticia']['olho'])?$noticia['Noticia']['olho']:(!empty($noticia['Noticia']['conteudo_preview']) ? $noticia['Noticia']['conteudo_preview'] : $noticia['Noticia']['conteudo']),
								132,
								array('ending' => '...','exact' => false,'html'=>false)
							)
						);
						?>
					</a>
					<?php
					//echo $this->Html->tag('br','');
				?></a></div><a class="noticia_count"></a><?php
				$i++;			
			}
			//echo $this->Html->tag('br','',array('class'=>'clear'));
			echo '<div class="clear"></div>'
		?>
		<div style="height:1px;background:#ccc;"></div>
		</div>
		<!-- Demais noticias -->
		<?php
		//Validacao se tiver mais noticias 
		//if(empty($noticias_lista)){echo '';}else{ ?>
		<div class="content_middle noticias_listar">
			<h4 class="mais"><?php __('Mais'); ?></h4>
			<?php foreach ($noticias_lista AS $key => $noticia){ ?>
				<a href="<?php echo $this->Html->url($noticia['Noticia']['link']); ?>">
					<div class="listar">
						<p class="titulo"><?php echo $noticia['Noticia']['titulo']; ?></p>
						<span class="data"><?php echo $noticia["Noticia"]["data_noticia_data"].' - '; ?>
							<span class="noticia">
							<?php
								echo $this->Text->truncate(
									!empty($noticia['Noticia']['olho'])?$noticia['Noticia']['olho']:(!empty($noticia['Noticia']['conteudo_preview']) ? $noticia['Noticia']['conteudo_preview'] : $noticia['Noticia']['conteudo']),
									100,
									array('ending' => '...','exact' => false,'html'=>false)
								);
							?>
							</span>
						</span>
					</div>
				</a>
			<?php } ?>
		</div>
		<div class="clear"></div>
		<div class="wrap paginacao">
			<?php 
				//$result_galerias = count($noticias);
				//if($result_galerias > 7){
					echo $this->element('paginacao');
				//} 
			 ?>
		
		</div>
		<?php //}//Validacao se tiver mais noticias ?>
		<?php endif; ?>
	</div>
<div class="linha" style="background-color:#c65727 !important"></div>
</div>
<div class="clear"></div

	
	
	
	
	