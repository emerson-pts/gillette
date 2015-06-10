<?php
echo $this->Html->script(array(
	'ui/minified/jquery.ui.core.min',
	'ui/minified/jquery.ui.widget.min',
	'ui/minified/jquery.ui.mouse.min',
	'ui/minified/jquery.ui.sortable.min',
), array('inline' => false));
?>
<script>
$(function() {
	$( ".disable-selection" ).disableSelection();
	$( ".sortable-list" ).sortable({
		helper: 'clone', 
		appendTo: 'body',
		update : function (event, ui) { 
			ajusta_mini_menu($('.sortable-list'));
			var order = ui.item.index()+1; 
			var row_id = ui.item.attr('row_id'); 
			
			ui.item.append('<div class="loading overlay"><div class="loading-circle"></div></div>');
			var url = "<?php echo $this->Html->url(array('action' => 'setposition'));?>/" + row_id + '/' + order;

			$.ajax({
				url: url,
				dataType: 'json',
				cache: false,
				type: 'get',
				context: ui.item,
				success: function(data){
					if(data != true){
						blockUIpage('<span class="picto-24 delete"></span> <?php __('Ocorreu um erro ao mover o item!');?><br />' + data + '<br /><br /><a href="?' + Math.random() + '" class="button small red"><?php __('Por favor, clique aqui para recarregar a página');?></a>');
					}

					$(this).find('div.loading.overlay').remove();	//Oculta ícone loading
					ajusta_mini_menu($(this).parent());				//Ajusta botões para subir e descer
				},
				error: function(){
					blockUIpage('<span class="picto-24 delete"></span> <?php __('Ocorreu um erro ao mover o item!');?><br /><br /><a href="?' + Math.random() + '" class="button small red"><?php __('Por favor, clique aqui para recarregar a página');?></a>');
				}
			
			});
		} 
	});
	ajusta_mini_menu($('.sortable-list'));
});

function ajusta_mini_menu(e_this){
	e_this.find('>li .mini-menu li').removeClass('hidden');
	//Oculta botão subir
	e_this.find('>li:first .mini-menu li[action=moveup]').addClass('hidden');
	e_this.find('>li:last .mini-menu li[action=movedown]').addClass('hidden');
}
	
</script>
<section class="grid_12">
	<div class="block-border"><div class="block-content disable-selection">
		<h1><?php echo __((isset($setup['pageTitle']) ? $setup['pageTitle'] : $controller));?></h1>
		<?php
			//Se definiu uma descrição para a página
			if(!empty($setup['pageDescriptionIndex'])):
				echo $this->Html->tag('p', $setup['pageDescriptionIndex']);
				//Fecha o block-content e abre outro sem título
				?>
				</div>
				<div class="block-content no-title">
				<?php
			endif;
		?>
		<div class="with-padding no-padding-top">
		<?php if(empty($results)):?>
			<p class="message"><?php if(array_key_exists('noRecords', $setup)){echo $setup['noRecords'];}else{echo 'Nenhum registro foi encontrado.';}?></p>
		<?php else: ?>
			<ul class="sortable-list">
			<?php
			foreach($results AS $key=>$r):
				$params = $setup['form']['arquivo'];
			?>
				<li class="ui-state-default" row_id="<?php echo $r[$model]['id'];?>">
					<?php 
					$show_preview_url = (
						!empty($params['show_preview_url']) ? 
							$params['show_preview_url'].'?src='.rawurlencode($r[$model]['arquivo'])
							:
							array('controller' => 'thumbs', '?' => array('src' => $r[$model]['arquivo']))
					);
					
					echo $this->Html->link(
						'<img src="'.$this->Html->url($show_preview_url). (!is_array($params['show_preview_thumb']) ? '&size='.rawurlencode($params['show_preview_thumb']) : '&'.implode('&', $params['show_preview_thumb'])).'" />',
						$show_preview_url. (!is_array($params['show_preview']) ? '&size='.rawurlencode($params['show_preview']) : '&'.implode('&', $params['show_preview'])),
						array('escape' => false, 'rel' => 'galeria', 'class' => 'fancy')
					);
					?><br />
					<ul class="mini-menu">
					<?php
					echo $this->Element('admin/list_actions', array('r' => $r, 'tag' => '<li>%s</li>',));
					foreach(array(/*'moveup' => 'Mover para a posição anterior', 'movedown' => 'Mover para a próxima posição',*/ 'edit' => 'Editar', 'delete' => 'Apagar', ) AS $action => $title){
						if(empty($acl) || !method_exists($acl, 'check') || $acl->check('controllers/'.$this->name.'/'.$action)){
							?><li action="<?php echo $action;?>"><?php echo $this->Html->link($this->Html->image('admin/icon-'.$action.'.gif'), array('action' => $action, $r[$model]['id']) + $this->params['named'], array('escape'=>false, 'class' => 'with-tip', 'title' => $title, ), ($action != 'delete' ? null : sprintf('Você tem certeza que deseja apagar o registro %s (cód. %s)?', $r[$model][$setup['displayField']], $r[$model]['id'])));?></li>
						<?php
						}
					}
					
					?></ul>
					<div class="display-field"><?php echo preg_replace('/([^ ])(<br \/>[^ ])/', '\1-\2', chunk_split($this->Text->truncate(strip_tags($r[$model][$setup['displayField']]), 30), 15, "<br />"));?></div>
				</li>
			<?php
			endforeach;
			?>
			</ul>
		<?php endif;?>
		</div>
		<div class="clear"></div>
	</div></div>
</section>

