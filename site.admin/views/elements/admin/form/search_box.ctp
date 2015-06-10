<?php
/*	<form id="search-form" name="search-form" method="post" action="search.html">
		<input type="text" name="s" id="s" value="" title="<?php __('Procurar por ...');?>" autocomplete="off">
	</form>
*/
$default_search_value = __('Busca', true);
echo $this->Form->create(false, array('id' => 'search-form', 'type' => 'get', 'action' => 'index', 'admin' => true));?>
		<?php if (!empty($this->params['named']['search']))echo $html->link($html->image("admin/icon-delete.png"), preg_replace('/\/?search:[^\/]+/', '', '/'.$this->params['url']['url']), array('escape' => false));?>
        <label><?php echo $this->Form->text('search', array('empty' => __('Busca', true), 'class' => 'with-tip disable-next-field-js', 'title' => __('Digite o que deseja encontrar', true), 'name'=>'search', 'value' => (!empty($this->params['named']['search']) ? $this->params['named']['search'] : $default_search_value))); ?></label>
<?php 
echo $this->Form->end();
echo $this->Html->scriptBlock('
	jQuery("input#search")
		.focus(function(){
			if(jQuery(this).val() == "'.$default_search_value.'")jQuery(this).val("");
		})
		.blur(function(){
			if(jQuery(this).val().match(/^ *$/))jQuery(this).val("'.$default_search_value.'");
		})
		.keyup(function(event) {
			if (event.keyCode == "13") {
				window.location.href = window.location.href.replace(/(\/$|\/search:[^\/]+)/,"") + "'.($this->params['url']['url'] == '/' ? '/'.$this->params['controller'].'/'.$this->params['action'] : (!preg_match('/^'.preg_quote($this->base.'/'.$this->params['controller'].'/'.$this->params['action'], '/').'(\/|$)/', $this->here) ? '/'.$this->action : '')).'/search:" + $(this).val().replace(/%/g, "*");
			}
   		})
   	;
');