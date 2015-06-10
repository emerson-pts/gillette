<?php
	echo $form->create('Log', array('url'=> array('controller' => 'logs', 'action' => 'index'), 'id' => 'LogAjax'));
	echo $form->hidden('model', array('value' => $this->params['models'][0]));
	echo $form->hidden('models', array('value' => serialize($log['models'])));
	echo $form->hidden('data', array('value' => serialize((!empty($log['data']) ? $log['data'] : array('true')))));
	echo $form->submit(__('Registro de alterações', true),array('div'=>false, 'class'=> 'small button'));
	echo $form->end();
?>
<script type="text/javascript">
$(document).ready(function(){ 
	$("form#LogAjax input[type=submit]").click(function(event){
		if($(this).hasClass('ajax'))return;
		$(this).addClass('ajax');
		$(this).parents("form:first").ajaxSubmit({ 
			dataType: "html",
			context: $(this).parents("form:first"),
			success: function(data) {
				$(this)
					.replaceWith('<div id="log_data" class="hidden">' + data + '</div>')
					.slideUp('normal', function(){
						$('#log_data').delay(400).fadeIn('slow');
					})
				;
			}
		});
		event.stopPropagation();
		event.preventDefault();
		return false;
	});
});
</script>