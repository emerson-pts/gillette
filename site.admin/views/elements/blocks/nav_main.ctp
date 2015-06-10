<!-- Main nav -->
<nav id="main-nav">
	<ul class="container_12">
	<?php
	$current_menu = $current_submenu = false;
	foreach($menu AS $key=>$value):
		
		//Se não tem subcategoria
		if (!is_array($value)):
			//Verifica se tem acesso a url
			if($value == '/' || $this->Acl->check('controllers'.preg_replace("#^(/[^/]+/[^/]+).*$#", "\\1",$value))):

				//Verifica se algum dos subitens está ativo. 
				$parseUrlValue = Router::parse($value);
				if(empty($current_menu) && $parseUrlValue['controller'] == $this->params['controller']
					&& $parseUrlValue['action'] == $this->params['action']
				):
					$current_menu = $key;
				endif;
				?>
				<li class="<?php echo strtolower(Inflector::slug($key, '-')) . ' ' . ($current_menu == $key ? 'current' : '');?>">
					<?php echo $this->Html->link($key, $value, array('title' => $key, 'class' => (preg_match('/^'.preg_quote($this->Html->url($value),'/').'/', $this->params['url']['url']) ? 'current' : '')));?>
				</li>
			<?php
			endif;
		else:
			//Se tem subcategoria monta item e submenu
				
			//Define link padrão do menu
			$subitem_default = '#';

			//Looping nos subitens
			foreach($value AS $v_key=>$v_value):
				//Armazena qual é o subitem não numérico mais recente
				//Utilizado para destacar o subitem ativo
				if(!is_numeric($v_key))$last_not_numeric_submenu = $v_value;
				
				//Verifica se tem acesso ao subitem
				if($v_value != '/' && !$this->Acl->check('controllers'.preg_replace("#^(/[^/]+/[^/]+).*$#", "\\1",$v_value))):
					//Remove subitem sem permissão de acesso
					unset($value[$v_key]);
					continue;
				endif;
				
				//Se o rótulo do subitem começa com *, utiliza como a url do menu principal
				if (preg_match('/^\*/',$v_key)):
					$subitem_default = $v_value;
				endif;
				
				$parseUrlValue = Router::parse($v_value);
				if(empty($current_menu)):

					//Remove valores dos índices named e pass, caso não tenham sido setados na url do item do menu
					foreach(array('named', 'pass',) AS $parse_key):
						if(empty($parseUrlValue[$parse_key])):
							unset($parseUrlValue[$parse_key]);
						else:
//							$this->params[$parse_key] = array_intersect_key($this->params[$parse_key], $parseUrlValue[$parse_key]);
							$parseUrlValue[$parse_key] = array_intersect_key($this->params[$parse_key], $parseUrlValue[$parse_key]);
						endif;
					endforeach;

					$diff = array_diff_assoc($parseUrlValue, $this->params);//Compara url do item e a rota atual
	
					//Se algum parametro da url for uma array, então compara seus valores
					foreach($parseUrlValue AS $p_key=>$p_value){
						if(is_array($p_value)){
							if(!array_key_exists($p_key, $this->params)){
								$diff += $p_value;
							}else{
								$diff += array_diff_assoc($p_value, $this->params[$p_key]);
							}
						}
					}
					if(empty($diff)):
						$current_menu = $key;
						//Se o submenu ativo tem um índice numérico
						if(is_numeric($v_key)):
							//Então o submenu ativo é o subitem que tiver uma chave não numérica (url)
							$current_submenu = $last_not_numeric_submenu;
						else:
							$current_submenu = $v_value;
						endif;
					endif;
				endif;
			endforeach;

			//Se tem itens no submenu...
			if(!empty($value)):
			?>
				<li class="<?php echo strtolower(Inflector::slug($key, '-')) . ' ' . ($current_menu == $key ? 'current' : '');?>">
					<?php echo $this->Html->link($key, $subitem_default, array('title' => $key,));?>
					<ul>
					<?php
					//Subitens
					foreach($value AS $v_key=>$v_value):
						//Não inclui subitens com índices numéricos no menu
						if(is_numeric($v_key))continue;
						
						if($v_value != '/' && !$this->Acl->check('controllers'.preg_replace("#^(/[^/]+/[^/]+).*$#", "\\1",$v_value)))continue;
	
						echo $this->Html->tag('li', $this->Html->link(preg_replace('/^\*/','',$v_key),$v_value), array(
							/* se a url do item termina com /, então verifica se está no mesmo controller*/
							/* caso contrário verifica se é exatamente a mesma ação*/
							'class' => ($current_submenu == $v_value ? 'current' : '')
						));
					endforeach;
					?>
					</ul>
				</li>
			<?php
			endif;
		endif;
	endforeach;
	?>
	</ul>
</nav>
<!-- End main nav -->
<script type="text/javascript">
jQuery(document).ready(function(event){
	jQuery('#main-nav>ul>li>a').dblclick(function(event){
		first_link = jQuery(this).parent().find('ul>li:first>a');
		if(first_link.size() == 1 && first_link.attr('href') != ''){
			window.location.href = first_link.attr('href');
			event.stopPropagation();
			event.preventDefault();
			return false;
		}
	});
});
</script>