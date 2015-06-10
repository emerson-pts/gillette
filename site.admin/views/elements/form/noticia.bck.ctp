<section class="grid_12">
	<div class="block-border"><div class="block-content">
		<?php
		 echo $this->Element('admin/form/default_form', 
			array(
				'form_title' => $form_title,
				'form_submit_label' => $form_submit_label,
			)
		);
		?>
	</div>
    <div class="block-content">
		<h1>Relacionamentos</h1>
		<fieldset>
			<?php
				echo $this->Form->input('noticia_relacionada_search',array('label' => 'Notícias', 'type' => 'text', 'after' => '<ul class="relacionamentos" id="NoticiaRelacionada"><li class="hidden"><a title="Remover" class="delete_related" href="javascript://Remover"><input type="hidden" name="data[NoticiaRelacionada][NoticiaRelacionada][]" value="" /><span></span></a></li></ul>', 'value' => '', 'div' => 'input nopadding-bottom'));
				echo $this->Form->hidden('noticia_relacionada_search_label');
			?>
			
		</fieldset>
		<?php echo $this->Form->end();?>
	</div></div>
</section>
<?php
echo $jmycake->autocomplete('NoticiaNoticiaRelacionadaSearch','/noticias/autocomplete/Noticia/{Noticia.id|Noticia.titulo|Noticia.friendly_url}',array('NoticiaNoticiaRelacionadaSearchLabel'=>'itemLabel',));
?>
<script type="text/javascript">
function NoticiaNoticiaRelacionadaSearch_onupdate(){
	relacionamento_update('NoticiaNoticiaRelacionadaSearch', 'NoticiaRelacionada', 'related_id');
}


function relacionamento_update(search_field, field, related_id){
	id = $('#' + search_field).val();
	$('#' + search_field).val('');
	label = $('#' + search_field + 'Label').val();
	$('#' + search_field + 'Label').val('');
	
	if($('#' + field  + ' li input[value="' + id + '"]').size() != 0)return;
	
	var $li_clone = $('#' + field + '>li:first').clone();
	
	$li_clone
		.find('input')
			.val(id)
	;
	$li_clone
		.find('span')
			.html(label)
	;
	$li_clone
		.appendTo('#' + field)
		.fadeIn()
	;
}

$(document).ready(function(){
	$('.delete_related').click(function(){
		if(!window.confirm('Deseja remover este relacionamento?')){
			event.stopPropagation();
			event.preventDefault();
			return false;
		}
		$(this).fadeOut('normal', function(){
			$(this).remove();
		});
	});
	
	<?php
	foreach(array(
		'NoticiaRelacionada' => '{titulo} ({data_noticia} - cód. {id})',
	) AS $key=>$value){
		echo '
			var $id = $("#Noticia'.$key.'Search");
			var $label = $("#Noticia'.$key.'SearchLabel");
		';
		if(is_array($this->data[$key])){
			foreach($this->data[$key] AS $relacionamento){
				$replaces = array();
				foreach(Set::flatten($relacionamento) AS $replace_key=>$replace_value){
					$replaces['/'.preg_quote('{'.$replace_key.'}', '/').'/']  = $replace_value;
				}
				echo '$id.val("'.$relacionamento['id'].'");';
				echo '$label.val("'.htmlentities(preg_replace(array_keys($replaces), $replaces, $value), $flags = ENT_COMPAT, $charset = 'UTF-8').'");';
				echo 'relacionamento_update("Noticia'.$key.'Search", "'.$key.'", "related_id");';
			}
		}
	}
	?>
});
</script>