<div class="wrap">
	<div class="content_middle">
	    	<div class="title-paginas">
				<div class="linha-title-paginas"></div>
					<center>
						<span>
							<?php echo $this->Html->image("deco_title_left.jpg", array("alt" => ""));?>
							<?php __('Notícias'); ?>
							<?php echo $this->Html->image("deco_title_right.jpg", array("alt" => ""));?>
	
						</span>
					</center>
				
			</div><!-- title-paginas --->
			<div class="btn-voltar"><?php echo $this->Html->link(__('Voltar', true), '/noticias/');?></div>
			<br />
			<?php 
				//Se não tem notícias
				if(empty($noticia)):
					echo $this->Html->div('message_error',__('Nenhum conteúdo foi encontrado.', true));
					$noticias_lista = $noticias;
				else:
					echo $this->Html->div('noticia-data-ver',$noticia["Noticia"]["data_noticia_data"].' &agrave;s '.$noticia['Noticia']["data_noticia_hora"]);
					//TÍTULO
					echo '<div class="noticia-ver">';
			 			echo $this->Html->tag('h3',$noticia["Noticia"]["titulo"]);
					echo '</div>';
					echo '<br />';
			 		//CONTEUDO
					?><div class="noticia-conteudo"><?php
						//IMAGE
						?><div class="noticia-img"><?php
							echo $this->Html->image(
							(!empty($noticia['Noticia']['image']))?array('controller'=>'thumbs','?'=>array('src'=>$noticia['Noticia']['image'],'size'=>'355*250','crop'=>'355x250')):'img-default/imagem-default-peq.jpg',
							array('width'=>'355','height'=>'250')
							)
						?>
						<div class="legenda-foto"><?php echo $noticia['Noticia']['image_legenda']; ?></div>

						</div>
					<?php
						//CONTEUDO	
						echo $this->Html->tag('span',$noticia['Noticia']['conteudo']);
					?></div><?php
					
					//SHARE THIS
			        ?>
					<br />
					<br />
					<span class="titulo_compartilhar"><?php __('COMPARTILHE'); ?></span>
					<span  class='st_twitter_large' ></span><span  class='st_facebook_large' ></span><span  class='st_yahoo_large' ></span><span  class='st_email_large' ></span><span  class='st_sharethis_large' ></span><script type="text/javascript">var switchTo5x=true;</script><script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script><script type="text/javascript">stLight.options({publisher:'a6c784ee-59c5-403c-9542-2cafb629b8ab'});</script>
					<br />
					<br />
					<br />
					<br />
				<?
			 		echo $this->Html->tag('br','',array('class'=>'clear'));
				endif;
			?>
	</div>
<div class="linha"></div>
</div>
	