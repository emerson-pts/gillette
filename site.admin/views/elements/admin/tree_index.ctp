<?php
echo $this->Html->script(array(
	'ui/minified/jquery.ui.core.min',
	'ui/minified/jquery.ui.widget.min',
	'ui/minified/jquery.ui.mouse.min',
	'ui/minified/jquery.ui.draggable.min',
	'ui/minified/jquery.ui.droppable.min',
));

/*
	$this->set('footerLink', array(
//		'<a href="//javascript: Salvar" title="Use Ctrl+S para salvar" class="with-tip button"><span class="picto save"></span> Salvar</a>',
		'<a href="//javascript: Desfazer" title="Use Ctrl+Z para desfazer" class="with-tip button nested-sortable-undo"><span class="picto undo"></span> Desfazer</a>',
	));
*/
?>
<script type="text/javascript">
//Reordenação basedo no script de Dave McDermid 
//http://boagworld.com/technology/creating-a-draggable-sitemap-with-jquery/
//PHP Cake
//http://blogs.bigfish.tv/adam/2008/02/12/drag-and-drop-using-ext-js-with-the-cakephp-tree-behavior/
var sitemapHistory = {
    stack: new Array(),
    temp: null,
    //takes an element and saves it's position in the sitemap.
    //note: doesn't commit the save until commit() is called!
    //this is because we might decide to cancel the move
    saveState: function(item) {
        sitemapHistory.temp = { item: $(item), itemParent: $(item).parent(), itemAfter: $(item).prev() };
    },
    commit: function() {
        if (sitemapHistory.temp != null) sitemapHistory.stack.push(sitemapHistory.temp);
    },
    //restores the state of the last moved item.
    restoreState: function() {
        var h = sitemapHistory.stack.pop();
        if (h == null) return;
        if (h.itemAfter.length > 0) {
            h.itemAfter.after(h.item);
        }
        else {
            h.itemParent.prepend(h.item);
        }
    }
}

function nestedSortableUpdateParent(e_this, post_data){ 
	e_this.find('>dl>span span.keyword').after('<span class="picto loading"></span>');
	$.ajax({
		url: "<?php echo $this->Html->url(array('action' => 'update_parent'));?>",
		data: {data: post_data},
		dataType: 'json',
		cache: false,
		type: 'post',
		context: e_this,
		success: function(data){
			if(data != true){
				blockUIpage('<span class="picto-24 delete"></span> <?php __('Ocorreu um erro ao mover o item!');?><br />' + data + '<br /><br /><a href="?' + Math.random() + '" class="button small red"><?php __('Por favor, clique aqui para recarregar a página');?></a>');
			}
			$(this).find('span.picto.loading').remove();	//Oculta ícone loading
			ajusta_mini_menu($(this).parent());				//Ajusta botões para subir e descer
			
			//Ajusta botões dos filhos do pai antigo
			if(post_data.current_parent == ''){
				ajusta_mini_menu($('.nested-sortable'));
			}else{
				ajusta_mini_menu($('li[tree_id=' + post_data.current_parent + ']>ul'));
			}
		},
		error: function(){
			blockUIpage('<span class="picto-24 delete"></span> <?php __('Ocorreu um erro ao mover o item!');?><br /><br /><a href="?' + Math.random() + '" class="button small red"><?php __('Por favor, clique aqui para recarregar a página');?></a>');
		}
	});
}

function nestedSortableUpdatePosition(e_this, post_data){ 
	e_this.find('>dl>span span.keyword').after('<span class="picto loading"></span>');
	
	if(post_data.current_index > post_data.new_index){
		var url = "<?php echo $this->Html->url(array('action' => 'moveup'));?>/" + post_data.id + '/' + (post_data.current_index - post_data.new_index);
	}else{
		var url = "<?php echo $this->Html->url(array('action' => 'movedown'));?>/" + post_data.id + '/' + (post_data.new_index - post_data.current_index);
	}
	$.ajax({
		url: url,
		dataType: 'json',
		cache: false,
		type: 'get',
		context: e_this,
		success: function(data){
			if(data != true){
				blockUIpage('<span class="picto-24 delete"></span> <?php __('Ocorreu um erro ao mover o item!');?><br />' + data + '<br /><br /><a href="?' + Math.random() + '" class="button small red"><?php __('Por favor, clique aqui para recarregar a página');?></a>');
			}
			
			$(this).find('span.picto.loading').remove();	//Oculta ícone loading
			ajusta_mini_menu($(this).parent());				//Ajusta botões para subir e descer
		},
		error: function(){
			blockUIpage('<span class="picto-24 delete"></span> <?php __('Ocorreu um erro ao mover o item!');?><br /><br /><a href="?' + Math.random() + '" class="button small red"><?php __('Por favor, clique aqui para recarregar a página');?></a>');
		}
	
	});
}

function ajusta_mini_menu(e_this){
	e_this.find('>li>dl .mini-menu li').removeClass('hidden');
	//Oculta botão subir
	e_this.find('>li:first>dl .mini-menu li[action=moveup]').addClass('hidden');
	e_this.find('>li:last>dl .mini-menu li[action=movedown]').addClass('hidden');
}

jQuery(function($){
	// Desfazer
/*	$('.nested-sortable-undo').click(sitemapHistory.restoreState);
    $(document).bind('keypress', function(e) {
        if (e.ctrlKey && (e.which == 122 || e.which == 26))
            sitemapHistory.restoreState();
    });
 */   
 
    // Cria reordenação
	$('.nested-sortable li:has(dl)')
	<?php if(empty($this->Acl) || !method_exists($this->Acl, 'check') || $this->Acl->check('controllers/'.$this->name.'/update_parent')):?>
		.addClass('draggable')
		.draggable({
			handle: ' > dl > span',
			opacity: .8,
			addClasses: false,
			helper: 'clone',
			zIndex: 100,
			start: function(event, ui){
//				$('.nested-sortable').find('dl,.dropzone').addClass('active');
				$(this).parent().find('.mini-menu').css({opacity:0, display: ''});
				sitemapHistory.saveState(this);
			},
			stop: function(event, ui){
				//Precisa ficar dentro do setTimeout senão não pega árvore atualizada
//				$('.nested-sortable').find('dl,.dropzone').removeClass('active');
				setTimeout(function(){
					$('.nested-sortable>li:last-child , .nested-sortable>li li:last-child').find('.dropzone.after').show(); //Exibe dropzones dos finais dos últimos elementos
					$('.nested-sortable>li:not(:last-child) , .nested-sortable>li li:not(:last-child)').find('>.dropzone.after').hide(); //Oculta dropzones dos finais dos elementos que não são mais os últimos
					
					//Remove ULs vazios
					$('.nested-sortable ul').filter(function() {
							return $.trim($(this).text()) === '' && $(this).children().length == 0
						})
						.remove()
					;

					$('.nested-sortable li:has(>dl) span.toggle').show();
					$('.nested-sortable li:not(:has(>ul)) span.toggle').hide();

				}, 100);
			}
		})
	<?php endif;?>
		.disableSelection()
		.prepend('<div class="dropzone before"></div>')
		.append('<div class="dropzone after"></div>')
	;

	$('.nested-sortable>li:not(:last-child) , .nested-sortable>li li:not(:last-child)').find('>.dropzone.after').hide();
	$('.nested-sortable li:not(:has(>ul)) span.toggle').hide();

	<?php if(empty($this->Acl) || !method_exists($this->Acl, 'check') || $this->Acl->check('controllers/'.$this->name.'/update_parent')):?>
	
	$('.nested-sortable li>dl, .nested-sortable .dropzone').droppable({
		accept: '.nested-sortable li:has(dl)',
		tolerance: 'pointer',
		drop: function(e, ui) {
			var li = $(this).parent();
			var child = !$(this).hasClass('dropzone');
			//If this is our first child, we'll need a ul to drop into.

			if (child && li.children('ul').length == 0) {
				li.find('.dropzone.after').before('<ul/>');
			}
			//ui.draggable is our reference to the item that's been dragged.
			if (child) {
				li.children('ul').append(ui.draggable);
			}
			else if($(this).hasClass('before')){
				li.before(ui.draggable);
			}
			else{
				li.after(ui.draggable);
			}
			//reset our background colours.
			li.find('dl,.dropzone').removeClass('hover');//css({ backgroundColor: '', height: '' });
    		sitemapHistory.commit();
    		
    		//Informações de índice e parent_id
    		var tree_id = ui.draggable.attr('tree_id');	//Tree id
    		var current_index = ui.draggable.attr('current_index'); 	//Índice atual
    		var current_parent = ui.draggable.attr('tree_parent_id');	//parent_id atual
    		
    		var new_index = ui.draggable.index(); 		//Novo índice
    		var new_parent = li.attr(($(this).hasClass('dropzone') ? 'tree_parent_id' : 'tree_id'));	//Novo parent_id

    		//Mudou de pai
    		if(new_parent != current_parent){
    			nestedSortableUpdateParent(ui.draggable, {
    				id: tree_id,
    				new_index: new_index,
    				new_parent: new_parent,
    				current_index: current_index,
    				current_parent: current_parent
    			});
    		}
    		//Só mudou de posição dentro do mesmo pai
    		//A verificação adicional é para checar evitar o update quando só tem um item ou já é o último item e arrastou para o pai
    		else if(current_index != new_index && !($(this).is('dl') && new_index-1 == current_index)){
    			nestedSortableUpdatePosition(ui.draggable, {
    				id: tree_id,
    				new_index: new_index,
    				new_parent: new_parent,
    				current_index: current_index,
    				current_parent: current_parent
    			});
    		}
    		    		
    		//Atualiza índice dos elementos do local de origem
    		$('.nested-sortable li[tree_id=' + current_parent + ']>ul').children().each(function(){
				$(this).attr('current_index', $(this).index())	//Atualiza índice dos próximos elementos
			});

    		ui.draggable
    			.attr('current_index', new_index)	//Atualiza índice no atributo
    			.attr('tree_parent_id', new_parent)	//Atualiza parent_id
    			.nextAll().each(function(){
    				$(this).attr('current_index', $(this).index())	//Atualiza índice dos próximos elementos
    			})
    		;
    		
   		},
		
		over: function() {
			$(this).filter('dl, .dropzone').addClass('hover');
			//css({ backgroundColor: '#aaa' });
			//$(this).filter('.dropzone').css({ backgroundColor: '#aaa', height: 25 });
		},
		out: function() {
			$(this).filter('dl,.dropzone').removeClass('hover');//css({ backgroundColor: '' });
//			$(this).filter('.dropzone').css({ backgroundColor: '', height: '' });
		}
	});
	<?php endif;?>
});
</script>
<section class="grid_12">
	<div class="block-border"><div class="block-content">
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
			<ul class="nested-sortable arbo with-mini-menu no-margin">
			<?php
			//Verifica os itens que podem subir ou descer
			foreach ($results as $i => $r):
				$results[$i]['moveup'] = false;
				$results[$i]['movedown'] = false;
				
				//Se não solicitou o fullpath id...
				if(!array_key_exists('fullpath_id', $r[$model])){
					trigger_error(__('Erro na configuração do modelo. É necessário setar o afterFindGetfullpath como array("id") ou array("id", "label").', true), E_USER_ERROR);
				}
	
				$dirname = dirname($r[$model]['fullpath_id']);
	
				foreach($results AS $i2=>$r2){
					if($i2 == $i)continue;
					if(preg_match('/^'.($dirname == '.' ? '' : preg_quote(dirname($r[$model]['fullpath_id']), '/').'\/').'[^\/]+$/', $r2[$model]['fullpath_id']) && $i2 < $i){
						$results[$i]['moveup'] = true;
						break;
					}
				}
								
				foreach($results AS $i2=>$r2){
					if($i2 == $i)continue;
					if(preg_match('/^'.($dirname == '.' ? '' : preg_quote(dirname($r[$model]['fullpath_id']), '/').'\/').'[^\/]+$/', $r2[$model]['fullpath_id']) && $i2 > $i){
						$results[$i]['movedown'] = true;
						break;
					}
				}
			endforeach;
	
			$depth = array();
			$index = array();
			for ($i = 0; $i < count($results); $i++):
				$r = $results[$i];
				
				if(!isset($index[$r[$model]['parent_id']])){
					$index[$r[$model]['parent_id']] = 0;
				}else{
					$index[$r[$model]['parent_id']]++;
				}
				
				//Fecha eventuais ul, caso seja de um pai diferente e não é também do id anterior
				if(count($depth) > 0 && isset($results[$i-1]) && $results[$i-1][$model]['parent_id'] != $r[$model]['parent_id'] && $results[$i-1][$model]['id'] != $r[$model]['parent_id']){
					for($depth_i = count($depth)-1;$depth_i >= 0; $depth_i--){
						if($depth[$depth_i] == $r[$model]['parent_id']){
							break;
						}
						unset($depth[$depth_i]);
						echo '</ul></li>';
					}
					$depth = array_merge($depth);
				}
			?>
				<li class="<?php if(isset($r[$model]['status'])){echo 'status_'.$r[$model]['status'];}?>" current_index="<?php echo $index[$r[$model]['parent_id']];?>" tree_id="<?php echo $r[$model]['id'];?>" tree_parent_id="<?php echo $r[$model]['parent_id'];?>"><span class="toggle"></span><dl><span><span class="keyword <?php if(!empty($r[$model]['color']))echo $r[$model]['color'].'-keyword';?>"><?php echo $r[$model][(!empty($setup['displayFieldTreeIndex']) ? $setup['displayFieldTreeIndex'] : $setup['displayField'])];?></span><ul class="mini-menu">
					<?php
					echo $this->Element('admin/list_actions', array('r' => $r, 'tag' => '<li>%s</li>',));
					foreach(array('add' => 'Novo subnível', 'movedown' => 'Mover para baixo', 'moveup' => 'Mover para Cima', 'edit' => 'Editar', 'delete' => 'Apagar', ) AS $action => $title){
						if(empty($this->Acl) || !method_exists($this->Acl, 'check') || $this->Acl->check('controllers/'.$this->name.'/'.$action)){
							?><li action="<?php echo $action;?>" class="<?php
							if((!$r['movedown'] && $action == 'movedown') || (!$r['moveup'] && $action == 'moveup')){
								echo 'hidden';
							}
							?>"><?php echo $this->Html->link($this->Html->image('admin/icon-'.$action.'.gif'), array('action' => $action, $r[$model]['id']), array('escape'=>false, 'class' => 'with-tip', 'title' => $title, ), ($action != 'delete' ? null : sprintf('Você tem certeza que deseja apagar o registro %s (cód. %s)?', $r[$model][$setup['displayField']], $r[$model]['id'])));?></li>
						<?php
						}
					}
				?></ul></span></dl>
				<?php
	
				//Se o próximo item é filho do item atual...
				if(isset($results[$i+1]) && $results[$i+1][$model]['parent_id'] == $r[$model]['id']){
					//Abre um ul
					$depth[] = $r[$model]['id'];
					?>
					<ul>
					<?php
				}else{
					//Fecha o li
					?>
					</li>
					<?php
				}
			endfor;
			for($depth_i = count($depth)-1;$depth_i >= 0; $depth_i--){
				echo '</ul></li>';
			}
			
			?>
			</ul>
		</div>
		<div class="clear"></div>
	</div></div>
</section>
