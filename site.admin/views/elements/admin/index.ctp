<?php
//Faz com que os endereços de página e ordenação gerados pelo paginator incluam as variáveis submetidas na url
$this->Paginator->options(array('url' => $this->passedArgs));

if(!isset($setup['searchField']) || $setup['searchField'])
	$this->set('searchBox', $this->element('admin/form/searchBox'));
?>
<section class="grid_<?php echo (!isset($setup['box_order']) && !isset($setup['box_filter']) ? '12' : '9');?>">
	<div class="block-border"><div class="block-content <?php if(empty($setup['pageDescriptionIndex']))echo 'no-padding';?>">
		<h1><?php echo __((isset($setup['pageTitle']) ? $setup['pageTitle'] : $controller));?></h1>
		<div class="block-controls">
            <?php
            echo $pageLinks = '<ul class="controls-buttons">
            	<li>'.$this->Paginator->counter(array('format' => __('Página %page% de %pages%' , true))).'</li>
				<li class="sep"></li>
				'.$this->Paginator->prev('<span class="picto navigation-left"></span> '.__('Anterior', true), array('tag' => 'li', 'escape' => false,), '<span class="picto navigation-left-disabled"></span> '.__('Anterior', true), array('tag' => 'li', 'escape' => false, 'class'=>'controls-block disabled')).'
				'.$this->Paginator->numbers(array(
					'tag' => 'li', 'before' => null, 'after' => null,
					'modulus' => '6', 'separator' => '', 'first' => null, 'last' => null,
				)).'
				'.$this->Paginator->next(__('Próxima', true).' <span class="picto navigation-right"></span>', array('tag' => 'li', 'escape' => false,), __('Próxima', true).' <span class="picto navigation-right-disabled"></span>', array('tag' => 'li', 'escape' => false, 'class'=>'disabled controls-block')).'
			</ul>';
			?>
		</div>
		<?php
			//Se definiu uma descrição para a página
			if(!empty($setup['pageDescriptionIndex'])):
				echo $this->Html->tag('p', $setup['pageDescriptionIndex']);
				//Fecha o block-content e abre outro sem título
				?>
				</div>
				<div class="block-content no-title no-padding">
				<?php
			endif;
		?>
		<div>
		<?php if(empty($results)):?>
			<p class="message"><?php if(array_key_exists('noRecords', $setup)){echo $setup['noRecords'];}else{echo 'Nenhum registro foi encontrado.';}?></p>
		<?php else: ?>
			<table id="<?php echo $model;?>TableIndex" class="table no-margin no-margin-bottom" cellspacing="0" width="100%">
				<thead>
					<tr><?php 
						//Cabeçalho da tabela
						//Executa looping nos campos a serem listados
						foreach($setup['listFields'] AS $field=>$field_setup): ?>
						<th scope="col" <?php if(is_array($field_setup) && !empty($field_setup['table_head_cell_param']))echo ' '.$field_setup['table_head_cell_param'];?>>
						<?php 
						//Se é para exibir links de ordenação...
						if(!is_array($field_setup) || (is_array($field_setup) && (empty($field_setup['no_sort']) || !empty($field_setup['sort'])))):?>
						<span class="column-sort">
							<a href="<?php echo $this->Html->url(array('sort' => (!empty($field_setup['sort']) ? $field_setup['sort'] : $field), 'direction' => 'asc'));?>" title="<?php __('Ordem Crescente')?>" class="sort-up <?php if((!empty($field_setup['sort']) ? $field_setup['sort'] : $field) == $this->Paginator->sortKey() && $this->Paginator->sortDir() == 'asc')echo 'active';?>"></a>
		                    <a href="<?php echo $this->Html->url(array('sort' => (!empty($field_setup['sort']) ? $field_setup['sort'] : $field), 'direction' => 'desc'));?>" title="<?php __('Ordem decrescente')?>" class="sort-down <?php if((!empty($field_setup['sort']) ? $field_setup['sort'] : $field) == $this->Paginator->sortKey() && $this->Paginator->sortDir() == 'desc')echo 'active';?>"></a>
		                </span>
						<?php endif; ?>
		                <?php
		                //Define o rótulo do cabeçalho
						if(!is_array($field_setup))://Se o valor não é uma array ...
							echo $field_setup;
						elseif(array_key_exists('label', $field_setup))://Se tem o índice label ...
							echo $field_setup['label'];
						else://Caso contrário usa o índice
							echo $field;
						endif;
						?>
					</th>
					<?php endforeach; ?><th scope="col" class="table-actions"><?php __('Ações');?></th>
					</tr>
				</thead>
				<tbody>
			<?php
			$i = 0;
			foreach ($results as $r):
				$class = null;
				if ($i++ % 2 == 0)$class = ' class="odd"';
			?>
				<tr<?php echo $class;?>>
					<?php 
					foreach($setup['listFields'] AS $field=>$field_setup):
						echo '<td'.(!empty($field_setup['table_body_cell_param']) ? ' '.$field_setup['table_body_cell_param'] : '').'>';
						
						$value = Set::extract($field, $r);

						//Se tem o índice field_format ...
						if(is_array($field_setup) && !empty($field_setup['field_format'])):
							//Se não é array
							if(!is_array($field_setup['field_format'])):
								//Então aplica valor como função
								$value = ${$field_setup['field_format']}($value);
							else:
								//Se não é array o índice 0 - tem somente uma ação a ser executada 
								if(!is_array($field_setup['field_format'][0])){
									//Transforma em uma array para executar o looping
									$setup['listFields'][$field]['field_format'] = $field_setup['field_format'] = array($field_setup['field_format']);
								}
								
								//Looping em todas formatações a serem executadas
								foreach($field_setup['field_format'] AS $field_format_key => $field_format):
									switch($field_format[0]){
										case 'function':
											switch(count($field_format)):
												case 2:
													$value = $field_format[1]($value);
													break;
												case 3:
													$value = $field_format[1]($value, $field_format[2]);
													break;
												case 4:
													$value = $field_format[1]($value, $field_format[2], $field_format[3]);
													break;
												case 5:
													$value = $field_format[1]($value, $field_format[2], $field_format[3], $field_format[4]);
													break;
												case 6:
													$value = $field_format[1]($value, $field_format[2], $field_format[3], $field_format[4], $field_format[5]);
													break;
												case 7:
													$value = $field_format[1]($value, $field_format[2], $field_format[3], $field_format[4], $field_format[5], $field_format[6]);
													break;
												default:
													debug(sprintf(__('Erro no parâmetro field_format do campo <i>%s</i>. <pre>%s</pre>', true), $field, var_export($field_format, true)));
													$this->cakeError('error500');
													break;
											endswitch;
											break;
											
										case 'object':
											switch(count($field_format)):
												case 3:
													$value = ${$field_format[1]}->$field_format[2]($value);
													break;
												case 4:
													$value = ${$field_format[1]}->$field_format[2]($value, $field_format[3]);
													break;
												case 5:
													$value = ${$field_format[1]}->$field_format[2]($value, $field_format[3], $field_format[4]);
													break;
												case 6:
													$value = ${$field_format[1]}->$field_format[2]($value, $field_format[3], $field_format[4], $field_format[5]);
													break;
												case 7:
													$value = ${$field_format[1]}->$field_format[2]($value, $field_format[3], $field_format[4], $field_format[5], $field_format[6]);
													break;
												default:
													debug(sprintf(__('Erro no parâmetro field_format do campo <i>%s</i>. <pre>%s</pre>', true), $field, var_export($field_format, true)));
													$this->cakeError('error500');
													break;
											endswitch;
											break;
											
										case 'class':
											$class = new $field_format[1];
											switch(count($field_format)):
												case 3:
													$value = $class->$field_format[2]($value);
													break;
												case 4:
													$value = $class->$field_format[2]($value, $field_format[3]);
													break;
												case 5:
													$value = $class->$field_format[2]($value, $field_format[3], $field_format[4]);
													break;
												case 6:
													$value = $class->$field_format[2]($value, $field_format[3], $field_format[4], $field_format[5]);
													break;
												case 7:
													$value = $class->$field_format[2]($value, $field_format[3], $field_format[4], $field_format[5], $field_format[6]);
													break;
												default:
													debug(sprintf(__('Erro no parâmetro field_format do campo <i>%s</i>. <pre>%s</pre>', true), $field, var_export($field_format, true)));
													$this->cakeError('error500');
													break;
											endswitch;
											break;
											
										default:
											debug(sprintf(__('Erro no parâmetro field_format do campo <i>%s</i>. O tipo de formatação não pode ser reconhecida (valores aceitos function, object e class). Informado: %s', true), $field, $field_format[0]));
											$this->cakeError('error500');
											break;
									}
									
								endforeach;
							endif;
						endif;
						
						//Se tem o índice field_printf
						if(is_array($field_setup) && array_key_exists('field_printf', $field_setup)):
							//Formata o valor do campo com o texto
							printf($field_setup['field_printf'], $value, $value, $value, $value, $value);
						else:
							echo $value;
						endif;
						
						echo '</td>';
					endforeach;
					?>
						<td class="table-actions">
							<?php 
							echo $this->Element('admin/list_actions', array('r' => $r));
							echo ((empty($acl) || !method_exists($acl, 'check') || $acl->check('controllers/'.$this->name.'/edit'))  && (!isset($setup['disable']) || !in_array('edit-link', $setup['disable'])) ? $this->Html->link('<span>' . __('Editar', true) . '</span>', array('action' => 'edit', $r[$model][(empty($setup['edit_id']) ? 'id' : $setup['edit_id'])]) + $this->params['named'],array('escape' => false, 'title' => 'Editar', 'class'=>'picto edit with-tip')) : '').
								' '.
								((empty($acl) || !method_exists($acl, 'check') || $acl->check('controllers/'.$this->name.'/delete'))  && (!isset($setup['disable']) || !in_array('delete-link', $setup['disable'])) ? $this->Html->link('<span>' . __('Apagar', true) . '</span>', array('action' => 'delete', $r[$model][(empty($setup['delete_id']) ? 'id' : $setup['delete_id'])]) + $this->params['named'], array('escape' => false, 'title' => 'Apagar', 'class'=>'picto delete with-tip'), sprintf('Você tem certeza que deseja apagar o registro %s (cód. %s)?', (isset($setup['displayField']) && isset($r[$model][$setup['displayField']]) ? $r[$model][$setup['displayField']] : ''), $r[$model][(empty($setup['delete_id']) ? 'id' : $setup['delete_id'])])) : '')
							; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<!-- Footer block -->
			<div class="no-margin block-footer">
				<div class="float-right">
					<?php echo $pageLinks;?>			 
				</div>
				<?php
					echo __('Limite de itens por página:').' <form class="inline" action="'.$this->Html->url(preg_replace('/\/?limit:[^\/]+/', '', '/'.preg_replace('/^\//','',$this->params['url']['url']))).'" method="get">
						<select name="limit" onchange="
							window.location.href = \''.$this->Html->url(preg_replace('/\/?limit:[^\/]+/', '', '/'.preg_replace('/^\//','',$this->params['url']['url']))).'/limit:\' + $(this).val().replace(/%/g, \'*\');
						">';
					$paginator_params = $this->Paginator->params();
					foreach(array(20,50,100,1000,5000) AS $limit){
						echo '<option value="'.$limit.'" '.($paginator_params['defaults']['limit'] == $limit ? 'selected' : '').'>'.$limit.'</option>';
					}
					echo '</select><noscript><input type="submit" value="OK" /></noscript></form>';
				?>
			</div>
		</div>
		<?php endif;?>
	</div></div>
</section>
<?php if(isset($setup['box_order']) || isset($setup['box_filter'])): ?>
<section class="grid_3">
	<?php
	if(isset($setup['box_order'])):
		echo '
			<div class="block-border with-margin"><div class="block-content">
			<div class="h1">Ordenação</div>
				<ul class="paginator-order">
		';
		//Exibe opções de ordenação
		foreach($setup['box_order'] AS $field => $label){
			echo '<li class="'.($field == $this->Paginator->sortKey() ? $this->Paginator->sortDir() : '').'">'.$this->Paginator->sort($label, $field).'</li>'."\r\n";
		}
		echo '
				</ul>
		</div></div>
		';
	endif;

	if(isset($setup['box_filter'])):
		foreach($setup['box_filter'] AS $filter=>$filter_setup){
			echo '
		<div class="block-border with-margin"><div class="block-content">
			<div class="h1">'.$filter_setup['title'].'</div>
				<ul class="paginator-order">';
			//Exibe opções
			foreach($filter_setup['options'] AS $id=>$label){
				echo '<li class="'.(
					(strcmp($id, '*') == 0 && !isset($this->params['named']['filter['.$filter.']'])) ||
					(isset($this->params['named']['filter['.$filter.']']) && strcmp($this->params['named']['filter['.$filter.']'], $id) == 0)
						? 'active' : '').'">'.$this->Html->link($label, preg_replace('/\/?filter\['.$filter.'\]:[^\/]+/', '', '/'.preg_replace('/^\//','',$this->params['url']['url'])).'/filter['.$filter.']:'.$id, array('escape' => false)).'</li>';
			}
			echo '</ul>
		</div></div>
			';
		}
	endif;
?>
</section>
<?php
endif;
?>
