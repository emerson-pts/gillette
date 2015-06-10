<div class="content_top"></div>
<div class="content_middle">
    <div class="linha-titulo-home"><?php
			echo $this->Html->tag('h2',(isset($menu_ativo[0]['Sitemap']['label']))?$menu_ativo[0]['Sitemap']['label']:'');
	       	echo $this->element('vendas');
	        echo $this->Html->tag('br','',array('class'=>'clear'));
	        
	?></div>
	<cake:nocache><?php echo $this->Session->flash('form');?></cake:nocache>
	
	
	<div class="content-text">
		<div class="message_success"><?php echo __('Sua mensagem foi enviada com sucesso!');?></div>		
		<br class="clear" />
	</div>
</div>
<div class="content_bottom"></div>