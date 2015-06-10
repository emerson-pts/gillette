<div class="menu_bg">
	<div class="wrap-menu">
		<div id="redes-sociais">
			
			<a target="_blank" href="<?php echo Configure::read('midias_sociais.facebook'); ?>"><?php echo $this->Html->image('icon-face.jpg'); ?> facebook.com/GilletteBrasil</a>
			<a target="_blank" href="<?php echo Configure::read('midias_sociais.twitter'); ?>"><?php echo $this->Html->image('icon-twiiter.jpg'); ?> GilletteBR</a>
			<a class="face-2" target="_blank" href="<?php echo Configure::read('midias_sociais.facebook2'); ?>"><?php echo $this->Html->image('icon-face.jpg'); ?> facebook.com/GilletteFedererTourBrasil</a>
		</div><!-- redes-sociais -->	
		<center>
        	<ul><?php
				$i = 0;
				?><!--<li<?php if(empty($menu_ativo)){echo ' class="active"';}?>><a href="<?php echo $this->Html->url('/');?>">HOME</a></li>--><?
				foreach($menu AS $item){
					$i++;
					$class = array();
					if($i == 1)$class[] = 'menu-left';
					if(count($menu) == $i)$class[] = 'menu-right';
					
					if((!empty($menu_ativo) && $menu_ativo[0]['Sitemap']['friendly_url'] == $item['Sitemap']['friendly_url'])
					
						|| ($this->params['controller'] == 'noticias' && $item['Sitemap']['friendly_url'] == 'noticias')
						
						|| ($this->params['controller'] == 'galerias' && $item['Sitemap']['friendly_url'] == 'galeria')
						
						|| ($this->params['controller'] == 'galerias' && $item['Sitemap']['friendly_url'] == 'galerias')
					)$class[] = 'active';
					echo '<li class="'.implode(' ', $class).'"><a href="'.$this->Html->url('/'.$item['Sitemap']['friendly_url']).'">'.$item['Sitemap']['label'].'</a>'."\r\n";
					if(!empty($item['submenu'])){
						echo '<ul>';
						foreach($item['submenu'] AS $subitem){
							echo '<li><a href="'.$this->Html->url('/'.$item['Sitemap']['friendly_url'].'/'.$subitem['Sitemap']['friendly_url']).'">'.$subitem['Sitemap']['label'] . '</a>';
							if(!empty($subitem['submenu'])){
								echo '<ul>'."\r\n";
								foreach($subitem['submenu'] AS $subitem2){
									echo '<li><a href="'.$this->Html->url('/'.$item['Sitemap']['friendly_url'].'/'.$subitem['Sitemap']['friendly_url']).'/'.$subitem2['Sitemap']['friendly_url'].'">'.$subitem2['Sitemap']['label'].'</a></li>'."\r\n";
								}
								echo '</ul>'."\r\n";
							}
							echo '</li>'."\r\n";
						}
						echo '</ul>'."\r\n";
					}
					echo '</li>'."\r\n";
				}
			?><br class="clear" />
			</ul>
		</center>	
	</div>
</div>
<script type="text/javascript">
//jQuery('.wrap-menu ul li:first').css('padding-left','40px');
jQuery('.wrap-menu ul li:last').css('border-right','0px');
</script>