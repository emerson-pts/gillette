<?php
//Faz com que os endereços de página e ordenação gerados pelo paginator incluam as variáveis submetidas na url
$paginator->options(array('url' => $this->passedArgs));

//Se os links superiores foram definidos
if(!empty($setup['topLink'])){
	$links = array();
	if(!is_array($setup['topLink']))$setup['topLink'] = array($setup['topLink']);
	foreach($setup['topLink'] AS $key=>$value){
		if(!isset($value['url'])){
			$value = array('url' => $value);
		}
		
		if(!isset($value['htmlAttributes']))$value['htmlAttributes'] = array();
		//if(!isset($value['htmlAttributes']['class']))$value['htmlAttributes']['class'] = array();
		//if(!in_array('topLink', $value['htmlAttributes']['class']))$value['htmlAttributes']['class'] = array();
		
		$links[] = $this->Html->link($key, $value['url'], $value['htmlAttributes']);
	}
	$this->set('topLink', implode( "\r\n", $links));
}

if(!isset($setup['searchField']) || $setup['searchField'])
	$this->set('searchBox', $this->element('admin/form/searchBox'));
?>
<div id="mid-col" class="<?php echo (!isset($setup['box_order']) && !isset($setup['box_filter']) ? 'total' : 'full');?>-col">
	<div class="box">
		<h4 class="white">
			<?php
			echo $pageLinks = '<div class="paging">'.
				$paginator->prev('<< '.__('Anterior', true), array(), null, array('class'=>'disabled')).
				'| '.$paginator->numbers().
				' |'.$paginator->next(__('Próxima', true).' >>', array(), null, array('class' => 'disabled')).'
			</div>'.$paginator->counter(array(
	'format' => __('%current%/%count% registros listados. Exibindo de %start% a %end%. Página %page% de %pages%.' , true)
	));
	?></h4>
		<div class="box-container nopadding">
			<?php
			$i = 0;
			foreach ($results as $r):
			?>
			<div class="align-left" style="width:207px;font-size: 11px;padding: 10px;margin: 10px 0 10px 0;border:1px solid #999;background: <?php echo ($r['Boletim']['status'] == 'aprovada' ? '#BBF3B4' : '#F3B4B7');?>">
				
				<small><?php echo $r['Boletim']['data'];?></small>
				<?php
					echo '<h3>'.$r['Boletim']['titulo'].'</h3><br />';
					$r['Boletim']['image'] = preg_replace('/\.admin/', '', $this->Html->url('/', true)).$r['Boletim']['image'];
					echo $this->Html->div(null,$this->Html->image($r['Boletim']['image'], array('style' => 'margin: 5px 0;-moz-box-shadow: 5px 5px 3px #aaa;-webkit-box-shadow: 5px 5px 3px #aaa;box-shadow: 5px 5px 3px #aaa;','height'=>'123')),array('style'=>'background-color:#e6e6e6;width:204px;margin-bottom:5px;height:144px;line-height:144px;display:block;text-align:center'));
				?><br class="clear"/>
				<div class="right">
				<?php 
					if(!empty($setup['listActions'])){
						foreach($setup['listActions'] AS $label=>$action){
							if(empty($action['url']['params'])){
								$action['url']['params'] = '';
							}else if(is_array($action['url']['params'])){
								foreach($action['url']['params'] AS $key=>$value){
									$action['url']['params'][$key] = precurrent(Set::extract($value, $r));
								}
								$action['url']['params'] = implode('/', $action['url']['params']);
							}else{
								$action['url']['params'] = preg_replace('/\{(.*?)\}/e', 'current(Set::extract("\1", $r))', $action['url']['params']);
							}
							echo (empty($acl) || !method_exists($acl, 'check') || $acl->check('controllers/'.(!is_array($action['url']) ? $action_url : $action['url']['controller'].(isset($action['url']['action']) ? '/'.$action['url']['action'] : ''))) ? $this->Html->link($label, (!is_array($action['url']) ? $action_url : '/'.$action['url']['controller'].(isset($action['url']['action']) ? '/'.$action['url']['action'] : '')).'/'.$action['url']['params'],(!empty($action['params']) ? $action['params'] : null)) : '');
						}
					
					}
					echo ((empty($acl) || !method_exists($acl, 'check') || $acl->check('controllers/'.$this->name.'/edit'))  && (!isset($setup['disable']) || !in_array('edit-link', $setup['disable'])) ? $this->Html->link('editar', array('action' => 'edit', $r[$model][(empty($setup['edit_id']) ? 'id' : $setup['edit_id'])]),array('class'=>'table-edit-link')) : '').
						((empty($acl) || !method_exists($acl, 'check') || $acl->check('controllers/'.$this->name.'/delete'))  && (!isset($setup['disable']) || !in_array('delete-link', $setup['disable'])) ? $this->Html->link('apagar', array('action' => 'delete', $r[$model][(empty($setup['delete_id']) ? 'id' : $setup['delete_id'])]), array('class'=>'table-delete-link'), sprintf('Você tem certeza que deseja apagar o registro %s (cód. %s)?', (isset($r[$model][$setup['displayField']]) ? $r[$model][$setup['displayField']] : ''), $r[$model][(empty($setup['delete_id']) ? 'id' : $setup['delete_id'])])) : '')
					;
				?>
				</div><br/>		
			</div>
			
			<br class="clear"/>
			<hr />
			<?php endforeach; ?>
			<br class="clear" />
			<br />
			<div style="padding: 10px;">
				<?php
				echo $pageLinks;
				?>
				<span class="clearFix">&nbsp;</span>
				<?php
					echo 'Limite de itens por página: <form class="inline" action="'.$this->Html->url(preg_replace('/\/?limit:[^\/]+/', '', '/'.preg_replace('/^\//','',$this->params['url']['url']))).'" method="get">
						<select name="limit" onchange="
							window.location.href = \''.$this->Html->url(preg_replace('/\/?limit:[^\/]+/', '', '/'.preg_replace('/^\//','',$this->params['url']['url']))).'/limit:\' + $(this).val().replace(/%/g, \'*\');
						">';
					$paginator_params = $paginator->params();
					foreach(array(20,50,100,1000,5000) AS $limit){
						echo '<option value="'.$limit.'" '.($paginator_params['defaults']['limit'] == $limit ? 'selected' : '').'>'.$limit.'</option>';
					}
					echo '</select><noscript><input type="submit" value="OK" /></noscript></form>';
				?>
			</div>
		</div><!-- end of div.box-container -->
	</div><!-- end of div.box -->
</div>
<div id="right-col">
<?php
if(isset($setup['box_order'])){
	echo '
	<div class="box">
		<h4 class="light-grey">Ordenação</h4>
		<div class="box-container">
			<ul class="paginator-order">
	';
	//Exibe opções de ordenação
	foreach($setup['box_order'] AS $field => $label){
		echo '<li class="'.($field == $paginator->sortKey() ? $paginator->sortDir() : '').'">'.$paginator->sort($label, $field).'</li>'."\r\n";
	}
	echo '
			</ul>
		</div><!-- end of div.box-container -->
	</div><!-- end of div.box -->
	';
}

if(isset($setup['box_filter'])){
	foreach($setup['box_filter'] AS $filter=>$filter_setup){
		echo '
	<div class="box">
		<h4 class="light-grey">'.$filter_setup['title'].'</h4>
		<div class="box-container">
			<ul class="paginator-order">';
		//Exibe opções
		foreach($filter_setup['options'] AS $id=>$label){
			echo '<li class="'.(
				((empty($id) || $id == '*') && empty($this->params['named']['filter['.$filter.']'])) ||
				(!empty($this->params['named']['filter['.$filter.']']) && $this->params['named']['filter['.$filter.']'] == $id)
					? 'active' : '').'">'.$this->Html->link($label, preg_replace('/\/?filter\['.$filter.'\]:[^\/]+/', '', '/'.preg_replace('/^\//','',$this->params['url']['url'])).'/filter['.$filter.']:'.$id, array('escape' => false)).'</li>';
		}
		echo '</ul>
		</div><!-- end of div.box-container -->
	</div><!-- end of div.box -->
		';
	}
}
?>
	<span class="clearFix"> </span>
</div>
<br clear="both" />
